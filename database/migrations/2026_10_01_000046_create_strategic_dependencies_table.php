<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategic_dependencies', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->string('dependency_type', 20)->default('internal');
            $t->foreignId('strategic_initiative_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('strategic_objective_id')->nullable()->constrained()->nullOnDelete();
            $t->string('depends_on');
            $t->text('description')->nullable();
            $t->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('risk_level', 20)->default('sedang');
            $t->text('mitigation')->nullable();
            $t->string('status', 20)->default('aktif');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategic_dependencies');
    }
};
