<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tows_strategy_issue', function (Blueprint $t) {
            $t->foreignId('tows_strategy_id')->constrained()->cascadeOnDelete();
            $t->foreignId('strategic_issue_id')->constrained()->cascadeOnDelete();
            $t->primary(['tows_strategy_id', 'strategic_issue_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tows_strategy_issue');
    }
};
