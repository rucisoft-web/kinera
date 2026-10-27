<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_analyses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('segment');
            $t->string('segment_group', 30)->nullable();
            $t->text('description')->nullable();
            $t->decimal('share_percent', 6, 2)->nullable();
            $t->decimal('revenue_contribution', 15, 2)->nullable();
            $t->string('growth', 20)->default('stabil');
            $t->boolean('is_target')->default(true);
            $t->text('needs')->nullable();
            $t->text('strategy')->nullable();
            $t->string('source')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_analyses');
    }
};
