<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('initiative_objective', function (Blueprint $t) {
            $t->foreignId('strategic_initiative_id')->constrained()->cascadeOnDelete();
            $t->foreignId('strategic_objective_id')->constrained()->cascadeOnDelete();
            $t->primary(['strategic_initiative_id', 'strategic_objective_id'], 'initiative_objective_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('initiative_objective');
    }
};
