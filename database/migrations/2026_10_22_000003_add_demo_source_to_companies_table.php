<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Sumber rujukan data demo (mis. laporan tahunan) untuk peringatan "data demo" di panel. */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $t) {
            $t->string('demo_source')->nullable()->after('is_demo');
        });
        // demo yang sudah terpasang sebelum kolom ini ada
        foreach (['DFI' => 'Laporan Tahunan 2025 PT Diamond Food Indonesia Tbk', 'HMN' => 'Laporan Tahunan 2025 PT Medikaloka Hermina Tbk'] as $code => $source) {
            DB::table('companies')->where('code', $code)->where('is_demo', true)->update(['demo_source' => $source]);
        }
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $t) {
            $t->dropColumn('demo_source');
        });
    }
};
