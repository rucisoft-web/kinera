<?php

namespace App\Services;

use App\Models\Finding;
use App\Models\StrategicInitiative;
use App\Support\DhastramContext;
use App\Support\Terms;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;

/** Laporan manajemen strategi & ekspor ke Excel. */
class DhastramReportService
{
    public const REPORTS = [
        'scorecard' => 'Kartu Skor Indikator Kinerja (bulanan & YTD)',
        'objectives' => 'Capaian Sasaran Strategis per Perspektif',
        'initiatives' => 'Status Inisiatif Strategis & Anggaran',
        'findings' => 'Temuan & Tindakan Korektif',
    ];

    public function __construct(
        protected StrategicScoreService $score,
        protected KpiScoringService $kpiScoring,
        protected InitiativePortfolioService $portfolio,
        protected DhastramContext $ctx,
    ) {}

    public function scorecard(): array
    {
        $rows = [];
        foreach ($this->score->objectives() as $o) {
            foreach ($o['kpis'] as $k) {
                $kpi = $k['kpi'];
                $rows[] = [
                    'perspective' => Terms::perspective($o['objective']->perspective),
                    'objective' => $o['objective']->label,
                    'kpi' => $kpi,
                    'monthly' => $this->kpiScoring->monthly($kpi),
                    'ytd' => $k,
                ];
            }
        }

        return $rows;
    }

    public function objectives(): array
    {
        $objectives = $this->score->objectives();

        return ['perspectives' => $this->score->perspectives($objectives), 'objectives' => $objectives, 'overall' => $this->score->overall()];
    }

    public function initiatives()
    {
        return $this->portfolio->portfolio();
    }

    public function findings()
    {
        return Finding::with(['correctiveActions.owner', 'raiser', 'review'])->orderBy('code')->get();
    }

    public function export(string $report): string
    {
        $period = $this->ctx->period();
        $company = $this->ctx->company();
        $file = storage_path('app/private/exports/'.($company?->code ?? 'X').'_'.$report.'_'.($period?->year ?? '').'_'.now()->format('Ymd_His').'.xlsx');
        @mkdir(dirname($file), 0775, true);
        $w = new Writer;
        $w->openToFile($file);
        $bold = (new Style)->setFontBold();
        $w->getCurrentSheet()->setName(mb_substr(self::REPORTS[$report] ?? $report, 0, 31));
        $w->addRow(Row::fromValues([($company?->name ?? '').' — '.(self::REPORTS[$report] ?? $report)], $bold));
        $w->addRow(Row::fromValues(['Perioda: '.($period?->name ?? '-'), 'Dicetak: '.now()->format('d-m-Y H:i')]));
        $w->addRow(Row::fromValues([]));

        switch ($report) {
            case 'scorecard':
                $head = ['Perspektif', 'Sasaran', 'Kode', 'IK', 'Satuan', 'Arah', 'Pemilik'];
                foreach (Terms::months() as $m => $name) {
                    $head[] = 'Target '.Terms::monthShort($m);
                    $head[] = 'Real '.Terms::monthShort($m);
                }
                $head = array_merge($head, ['Target YTD', 'Realisasi YTD', 'Skor YTD', 'Status']);
                $w->addRow(Row::fromValues($head, $bold));
                foreach ($this->scorecard() as $r) {
                    $vals = [$r['perspective'], $r['objective'], $r['kpi']->short_code, $r['kpi']->name, $r['kpi']->unit,
                        $r['kpi']->direction === 'lower' ? 'Makin kecil makin baik' : 'Makin besar makin baik', $r['kpi']->owner?->name];
                    foreach ($r['monthly'] as $mv) {
                        $vals[] = $mv['target'];
                        $vals[] = $mv['actual'] === null ? null : (float) $mv['actual'];
                    }
                    $vals = array_merge($vals, [$r['ytd']['target'], $r['ytd']['actual'], $r['ytd']['score'], $r['ytd']['status']]);
                    $w->addRow(Row::fromValues($vals));
                }
                break;
            case 'objectives':
                $d = $this->objectives();
                $w->addRow(Row::fromValues(['Skor strategis', $d['overall']['score'], $d['overall']['status']], $bold));
                $w->addRow(Row::fromValues(['Perspektif', 'Skor', 'Status', 'Jumlah sasaran'], $bold));
                foreach ($d['perspectives'] as $p) {
                    $w->addRow(Row::fromValues([$p['label'], $p['score'], $p['status'], $p['objectives']]));
                }
                $w->addRow(Row::fromValues([]));
                $w->addRow(Row::fromValues(['Perspektif', 'Kode', 'Sasaran', 'Penanggung jawab', 'Bobot', 'Jumlah IK', 'Skor', 'Status'], $bold));
                foreach ($d['objectives'] as $o) {
                    $w->addRow(Row::fromValues([Terms::perspective($o['objective']->perspective), $o['objective']->short_code, $o['objective']->title,
                        $o['objective']->owner?->name, (float) $o['objective']->weight, $o['kpis']->count(), $o['score'], $o['status']]));
                }
                break;
            case 'initiatives':
                $w->addRow(Row::fromValues(['Kode', 'Inisiatif', 'PIC', 'Status', 'Mulai', 'Selesai', 'Progres %', 'Waktu berjalan %', 'Terlambat', 'Kuadran', 'Anggaran (Rp)', 'Realisasi (Rp)', 'Serapan %', 'Milestone berikutnya'], $bold));
                foreach ($this->initiatives() as $r) {
                    $i = $r['initiative'];
                    $w->addRow(Row::fromValues([$i->short_code, $i->title, $i->owner?->name, StrategicInitiative::STATUSES[$i->status] ?? $i->status,
                        $i->start_date?->format('d-m-Y'), $i->end_date?->format('d-m-Y'), (float) $i->progress, $r['elapsed'], $r['late'] ? 'Ya' : 'Tidak',
                        InitiativePortfolioService::QUADRANTS[$r['quadrant']]['label'], $r['planned'], $r['realized'], $r['absorption'],
                        $r['next_milestone'] ? $r['next_milestone']->name.' ('.$r['next_milestone']->due_date->format('d-m-Y').')' : '-']));
                }
                break;
            case 'findings':
                $w->addRow(Row::fromValues(['Kode', 'Temuan', 'Sumber', 'Tingkat', 'Nilai (Rp)', 'Status', 'Tindakan korektif', 'PIC', 'Tenggat', 'Status tindakan'], $bold));
                foreach ($this->findings() as $f) {
                    $actions = $f->correctiveActions->isEmpty() ? [null] : $f->correctiveActions->all();
                    foreach ($actions as $a) {
                        $w->addRow(Row::fromValues([$f->short_code, $f->title, Finding::SOURCES[$f->source] ?? $f->source, Finding::SEVERITIES[$f->severity] ?? $f->severity,
                            $f->amount, Finding::STATUSES[$f->status] ?? $f->status, $a?->title, $a?->owner?->name, $a?->due_date?->format('d-m-Y'), $a?->status]));
                    }
                }
                break;
        }
        $w->close();

        return $file;
    }
}
