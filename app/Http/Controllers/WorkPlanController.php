<?php

namespace App\Http\Controllers;

use App\Models\WorkPlanDraft;
use App\Support\DemoNotice;
use App\Support\BscContext;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/** Cetak Draf Rencana Kerja: tampilan cetak (simpan PDF dari peramban) atau unduhan Word (.doc). */
class WorkPlanController extends Controller
{
    public function __invoke(Request $request, WorkPlanDraft $draft, BscContext $ctx)
    {
        abort_unless($request->user()?->hasPermission('report.view'), 403);
        abort_unless((int) $draft->company_id === (int) $ctx->companyId() && $draft->status === 'selesai', 404);

        $word = $request->query('format') === 'word';
        $html = view('rencana-kerja.draft', [
            'draft' => $draft,
            'f' => $draft->content['facts'] ?? [],
            'ai' => $draft->content['ai'] ?? [],
            'company' => $ctx->company(),
            'demo' => DemoNotice::for($ctx->company()),
            'word' => $word,
        ])->render();

        if (! $word) {
            return response($html);
        }

        $name = 'Rencana-Kerja-'.Str::slug($ctx->company()?->code ?: 'instansi').'-'.$draft->year.'-'.$draft->id.'.doc';

        return response("\u{FEFF}".$html, 200, [
            'Content-Type' => 'application/msword; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$name.'"',
        ]);
    }
}
