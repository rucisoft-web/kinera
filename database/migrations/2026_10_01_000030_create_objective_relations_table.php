<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('objective_relations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->foreignId('source_objective_id')->constrained('strategic_objectives')->cascadeOnDelete();
            $t->foreignId('target_objective_id')->constrained('strategic_objectives')->cascadeOnDelete();
            $t->unsignedTinyInteger('strength')->default(2);
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objective_relations');
    }
};
