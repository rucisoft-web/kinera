<?php

namespace App\Http\Controllers;

use App\Services\RenstraDraftService;
use App\Support\DemoNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/** Draf Renstra dari isian Kinera: tampilan cetak (simpan PDF dari peramban) atau unduhan Word (.doc). */
class RenstraController extends Controller
{
    public function __invoke(Request $request, RenstraDraftService $svc)
    {
        abort_unless($request->user()?->hasPermission('report.view'), 403);

        $data = $svc->build($request->integer('mulai') ?: null);
        abort_if($data['company'] === null, 404);

        $word = $request->query('format') === 'word';
        $html = view('renstra.draft', $data + [
            'demo' => DemoNotice::for($data['company']),
            'word' => $word,
        ])->render();

        if (! $word) {
            return response($html);
        }

        $name = 'Draf-Renstra-'.Str::slug($data['company']->code ?: $data['company']->name).'-'.$data['start'].'-'.$data['end'].'.doc';

        return response("\u{FEFF}".$html, 200, [
            'Content-Type' => 'application/msword; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$name.'"',
        ]);
    }
}
