<?php

namespace Tests\Feature;

use App\Models\DataImport;
use App\Models\Kpi;
use App\Models\KpiActual;
use App\Models\KpiTarget;
use App\Models\StrategicObjective;
use App\Services\Import\BscImporter;
use App\Services\Import\BscTemplate;
use App\Services\Import\BscWorkbook;
use OpenSpout\Reader\XLSX\Reader;
use Tests\TestCase;

class ImportTest extends TestCase
{
    protected function structure(float $march = 120): array
    {
        return [
            'Sasaran' => [
                ['F1', 'Keuangan', 'Meningkatkan pendapatan', null, null, 2, null],
                ['C1', 'kepuasan pelanggan', 'Pelanggan puas', null, null, '1,5', null],
            ],
            'Relasi Sasaran' => [['C1', 'F1', 3, null]],
            'KPI' => [
                ['F1.1', 'F1', 'Pendapatan', 'Rp juta', 'naik', 'bulanan', 'jumlah', 1, null, '1.200', 0, 'ya', 'tidak', null, null, null],
                ['C1.1', 'C1', 'Keluhan', 'kasus', 'turun', 'bulanan', 'jumlah', 1, null, null, 0, 'tidak', 'tidak', null, null, null],
            ],
            'Target' => [
                ['F1.1', 100, 100, 100, null, null, null, null, null, null, null, null, null, null],
                ['C1.1', 10, 10, 10, null, null, null, null, null, null, null, null, null, null],
            ],
            'Realisasi' => [
                ['F1.1', 90, '110', $march, null, null, null, null, null, null, null, null, null, 'Laporan', null],
            ],
        ];
    }

    public function test_import_creates_structure_targets_and_draft_actuals(): void
    {
        [$company, $period] = $this->company('AAA');
        $admin = $this->user('admin@aaa.test', 'company-admin', $company);

        $result = app(BscImporter::class)->run($this->workbook($this->structure()), $period, $admin, ['file_name' => 'aaa.xlsx']);

        $this->assertSame(0, $result->errorCount(), json_encode($result->issues));
        $this->useContext($period);
        $this->assertSame(2, StrategicObjective::count());
        $this->assertSame('pelanggan', StrategicObjective::where('code', 'C1')->value('perspective'));
        $this->assertEquals(1.5, StrategicObjective::where('code', 'C1')->value('weight'));
        $kpi = Kpi::where('code', 'F1.1')->first();
        $this->assertEquals(1200, $kpi->annual_target);
        $this->assertTrue($kpi->is_financial);
        $this->assertSame('lower', Kpi::where('code', 'C1.1')->value('direction'));
        $this->assertSame(6, KpiTarget::count());
        $this->assertSame(3, KpiActual::count());
        $this->assertSame(['draf'], KpiActual::pluck('status')->unique()->values()->all());
        $this->assertSame(1, DataImport::count());
    }

    public function test_reimport_updates_instead_of_duplicating(): void
    {
        [$company, $period] = $this->company('AAA');
        $admin = $this->user('admin@aaa.test', 'company-admin', $company);
        $importer = app(BscImporter::class);
        $importer->run($this->workbook($this->structure()), $period, $admin);

        $again = $importer->run($this->workbook($this->structure(150)), $period, $admin);

        $this->useContext($period);
        $this->assertSame(2, Kpi::count());
        $this->assertSame(3, KpiActual::count());
        $this->assertEquals(150, KpiActual::where('month', 3)->value('actual'));
        $this->assertSame(1, $again->summary['Realisasi']['diubah']);
        $this->assertSame(2, $again->summary['Realisasi']['sama']);
        $this->assertSame(2, $again->summary['KPI']['sama']);
    }

    public function test_bad_rows_are_reported_and_good_rows_still_imported(): void
    {
        [$company, $period] = $this->company('AAA');
        $admin = $this->user('admin@aaa.test', 'company-admin', $company);
        $sheets = $this->structure();
        $sheets['Sasaran'][] = ['X1', 'Pemasaran', 'Perspektif salah', null, null, 1, null];
        $sheets['KPI'][] = ['Z9', 'TIDAKADA', 'KPI tanpa sasaran', '%', 'naik', null, null, null, null, null, null, null, null, null, 'orang@luar.test', null];
        $sheets['Target'][] = ['Z9', 1, null, null, null, null, null, null, null, null, null, null, null, null];

        $result = app(BscImporter::class)->run($this->workbook($sheets), $period, $admin);

        $this->useContext($period);
        $this->assertSame(2, StrategicObjective::count());
        $this->assertSame(2, Kpi::count());
        $this->assertSame(3, $result->errorCount());
        $messages = collect($result->issues)->pluck('pesan')->join(' | ');
        $this->assertStringContainsString('Perspektif tidak dikenali', $messages);
        $this->assertStringContainsString('Kode sasaran "TIDAKADA"', $messages);
        $this->assertStringContainsString('Kode KPI "Z9"', $messages);
        $this->assertFalse(collect($result->issues)->contains(fn ($i) => $i['baris'] === null), 'setiap masalah menyebut nomor baris');
        $this->assertTrue(collect($result->issues)->contains(fn ($i) => $i['level'] === 'peringatan' && str_contains($i['pesan'], 'orang@luar.test')) === false,
            'KPI Z9 gagal, jadi peringatan pemiliknya ikut dibatalkan');
    }

    public function test_preview_does_not_save_anything(): void
    {
        [$company, $period] = $this->company('AAA');
        $admin = $this->user('admin@aaa.test', 'company-admin', $company);

        $result = app(BscImporter::class)->run($this->workbook($this->structure()), $period, $admin, [], dryRun: true);

        $this->assertSame(2, $result->summary['KPI']['dibuat']);
        $this->useContext($period);
        $this->assertSame(0, Kpi::count());
        $this->assertSame(0, DataImport::count());
    }

    public function test_rollback_restores_previous_values_and_only_latest_can_be_undone(): void
    {
        [$company, $period] = $this->company('AAA');
        $admin = $this->user('admin@aaa.test', 'company-admin', $company);
        $importer = app(BscImporter::class);
        $first = $importer->run($this->workbook($this->structure()), $period, $admin)->import;
        $second = $importer->run($this->workbook($this->structure(150)), $period, $admin)->import;

        $this->assertFalse($first->fresh()->canRollback());
        $importer->rollback($second, $admin);
        $this->useContext($period);
        $this->assertEquals(120, KpiActual::where('month', 3)->value('actual'));

        $this->assertTrue($first->fresh()->canRollback());
        $importer->rollback($first->fresh(), $admin);
        $this->assertSame(0, Kpi::count());
        $this->assertSame(0, StrategicObjective::count());
        $this->assertSame('dibatalkan', $first->fresh()->status);
    }

    public function test_approve_option_requires_permission(): void
    {
        [$company, $period] = $this->company('AAA');
        $office = $this->user('office@aaa.test', 'strategy-office', $company);   // tanpa kpi.approve_actual
        $boss = $this->user('boss@aaa.test', 'pimpinan', $company);

        app(BscImporter::class)->run($this->workbook($this->structure()), $period, $office, ['approve_actuals' => true]);
        $this->useContext($period);
        $this->assertSame(['draf'], KpiActual::pluck('status')->unique()->values()->all());

        app(BscImporter::class)->run($this->workbook(['Realisasi' => $this->structure(130)['Realisasi']]), $period, $boss, ['approve_actuals' => true]);
        $this->assertSame('disetujui', KpiActual::where('month', 3)->value('status'));
        $this->assertSame($boss->id, KpiActual::where('month', 3)->value('approved_by'));
    }

    public function test_data_of_each_company_stays_separate(): void
    {
        [$a, $periodA] = $this->company('AAA');
        [$b, $periodB] = $this->company('BBB');
        $admin = $this->user('root@ruci.test', 'super-admin', null);
        app(BscImporter::class)->run($this->workbook($this->structure()), $periodA, $admin);

        $this->useContext($periodB);
        $this->assertSame(0, Kpi::count());
        $this->useContext($periodA);
        $this->assertSame(2, Kpi::count());
    }

    public function test_template_and_export_use_the_same_sheets(): void
    {
        [$company, $period] = $this->company('AAA');
        $admin = $this->user('admin@aaa.test', 'company-admin', $company);
        app(BscImporter::class)->run($this->workbook($this->structure()), $period, $admin);

        $exported = app(BscTemplate::class)->export(sys_get_temp_dir().'/ruci-export.xlsx', $period);
        $reader = new Reader;
        $reader->open($exported);
        $names = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            $names[] = $sheet->getName();
        }
        $reader->close();
        $this->assertSame(array_merge(['Petunjuk'], array_keys(BscWorkbook::SHEETS)), $names);

        // export -> import kembali ke perusahaan lain menghasilkan struktur yang sama
        [$copy, $copyPeriod] = $this->company('CCC');
        $result = app(BscImporter::class)->run($exported, $copyPeriod, $this->user('root@ruci.test', 'super-admin', null));
        $this->assertSame(0, $result->errorCount(), json_encode($result->issues));
        $this->useContext($copyPeriod);
        $this->assertSame(2, Kpi::count());
        $this->assertSame(6, KpiTarget::count());
    }

    public function test_number_parsing_accepts_indonesian_formats(): void
    {
        $this->assertSame(11098665.0, BscWorkbook::number('11.098.665'));
        $this->assertSame(6.62, BscWorkbook::number('6,62'));
        $this->assertSame(1234.5, BscWorkbook::number('Rp 1.234,5'));
        $this->assertSame(3.75, BscWorkbook::number(3.75));
        $this->assertSame(12.0, BscWorkbook::number('12%'));
        $this->assertNull(BscWorkbook::number(' '));
    }
}
