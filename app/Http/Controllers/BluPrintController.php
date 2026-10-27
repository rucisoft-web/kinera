<?php

namespace App\Http\Controllers;

use App\Models\PerformanceContract;
use App\Services\BluContractService;
use App\Support\DemoNotice;
use App\Support\BscContext;
use Illuminate\Http\Request;

/** Dokumen cetak Kontrak Kinerja BLU sesuai format lampiran PER-6/PB/2025 (dicetak/disimpan PDF dari peramban). */
class BluPrintController extends Controller
{
    public const DOCS = [
        'kontrak' => 'Kontrak Kinerja (Lampiran I)',
        'berita-acara' => 'Berita Acara Hasil Pembahasan Usulan Kontrak Kinerja (Lampiran II)',
        'definisi-operasional' => 'Definisi Operasional Indikator Kinerja',
        'capaian' => 'Hasil Perhitungan Capaian Indikator Kinerja (format Lampiran VI)',
        'pernyataan' => 'Surat Pernyataan Kebenaran Perhitungan Kelebihan Capaian Kinerja (Lampiran IV)',
    ];

    public function __invoke(Request $request, string $doc, PerformanceContract $contract, BluContractService $svc, BscContext $ctx)
    {
        abort_unless($request->user()?->hasPermission('kpi.view'), 403);
        abort_unless(array_key_exists($doc, self::DOCS), 404);
        abort_unless((int) $contract->company_id === (int) $ctx->companyId() && (int) $contract->analysis_period_id === (int) $ctx->periodId(), 404);

        $upto = in_array((int) $request->query('s'), [3, 6, 9, 12], true) ? (int) $request->query('s') : 12;

        return view('blu.print', [
            'doc' => $doc,
            'title' => self::DOCS[$doc],
            'contract' => $contract,
            'company' => $ctx->company(),
            'period' => $ctx->period(),
            'demo' => DemoNotice::for($ctx->company()),
            'upto' => $upto,
            'a' => in_array($doc, ['capaian', 'pernyataan'], true) ? $svc->achievements($upto, $contract) : null,
        ]);
    }
}
