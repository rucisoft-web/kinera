<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpis', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('code', 40)->nullable();
            $t->foreignId('strategic_objective_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->text('description')->nullable();
            $t->string('unit', 30);
            $t->string('direction', 10)->default('higher');
            $t->string('frequency', 20)->default('bulanan');
            $t->string('aggregation', 10)->default('sum');
            $t->text('formula')->nullable();
            $t->string('data_source')->nullable();
            $t->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $t->decimal('weight', 6, 2)->default(1);
            $t->decimal('baseline', 18, 4)->nullable();
            $t->decimal('annual_target', 18, 4)->nullable();
            $t->unsignedTinyInteger('decimals')->default(0);
            $t->boolean('is_financial')->default(false);
            $t->boolean('is_critical')->default(false);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};
