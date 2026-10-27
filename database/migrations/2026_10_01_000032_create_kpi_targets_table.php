<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_targets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->foreignId('kpi_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('month');
            $t->decimal('target', 18, 4);
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->unique(['kpi_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_targets');
    }
};
