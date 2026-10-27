<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dhastram_sequences', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->nullable()->constrained()->cascadeOnDelete();
            $t->string('prefix', 20);
            $t->unsignedSmallInteger('year');
            $t->unsignedInteger('last_number')->default(0);
            $t->timestamps();
            $t->unique(['company_id', 'analysis_period_id', 'prefix', 'year'], 'dhastram_seq_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dhastram_sequences');
    }
};
