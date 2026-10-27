<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Instalasi lama memakai nama tabel dhastram_sequences: cukup ganti namanya.
        if (Schema::hasTable('dhastram_sequences')) {
            Schema::rename('dhastram_sequences', 'bsc_sequences');

            return;
        }

        Schema::create('bsc_sequences', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->nullable()->constrained()->cascadeOnDelete();
            $t->string('prefix', 20);
            $t->unsignedSmallInteger('year');
            $t->unsignedInteger('last_number')->default(0);
            $t->timestamps();
            $t->unique(['company_id', 'analysis_period_id', 'prefix', 'year'], 'bsc_seq_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bsc_sequences');
    }
};
