<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kpis', function (Blueprint $t) {
            $t->string('indicator_type', 10)->nullable()->after('direction');
        });

        // IK lama: hasil (lag) untuk Pemangku Kepentingan & Keuangan, pendorong (lead) untuk Proses & Pembelajaran
        foreach (DB::table('strategic_objectives')->select('id', 'perspective')->get() as $o) {
            DB::table('kpis')->where('strategic_objective_id', $o->id)
                ->update(['indicator_type' => in_array($o->perspective, ['pelanggan', 'keuangan'], true) ? 'lag' : 'lead']);
        }
        $demo = DB::table('companies')->where('is_demo', true)->pluck('id');
        foreach (\Database\Seeders\UinSukaSeeder::INDICATOR_TYPE_OVERRIDES as $code => $type) {
            DB::table('kpis')->whereIn('company_id', $demo)->where('code', $code)->update(['indicator_type' => $type]);
        }
    }

    public function down(): void
    {
        Schema::table('kpis', function (Blueprint $t) {
            $t->dropColumn('indicator_type');
        });
    }
};
