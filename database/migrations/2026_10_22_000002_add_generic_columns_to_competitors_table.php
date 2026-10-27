<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competitors', function (Blueprint $t) {
            $t->decimal('revenue', 18, 2)->nullable()->after('data_period');
            $t->decimal('revenue_growth', 8, 2)->nullable()->after('revenue');
            $t->decimal('net_margin', 8, 2)->nullable()->after('revenue_growth');
            $t->decimal('market_share', 6, 2)->nullable()->after('net_margin');
        });
    }

    public function down(): void
    {
        Schema::table('competitors', function (Blueprint $t) {
            $t->dropColumn(['revenue', 'revenue_growth', 'net_margin', 'market_share']);
        });
    }
};
