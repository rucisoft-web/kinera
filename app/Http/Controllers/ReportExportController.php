<?php

namespace App\Http\Controllers;

use App\Services\BscReportService;
use Illuminate\Http\Request;

class ReportExportController extends Controller
{
    public function __invoke(Request $request, string $report, BscReportService $svc)
    {
        abort_unless($request->user()?->hasPermission('report.export'), 403, 'Butuh izin report.export');
        abort_unless(array_key_exists($report, BscReportService::REPORTS), 404);
        $file = $svc->export($report);

        return response()->download($file, basename($file))->deleteFileAfterSend();
    }
}
