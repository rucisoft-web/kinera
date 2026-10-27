<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('initiative_budgets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->foreignId('strategic_initiative_id')->constrained()->cascadeOnDelete();
            $t->string('item');
            $t->string('category', 20)->default('opex');
            $t->decimal('planned_amount', 18, 2)->default(0);
            $t->decimal('realized_amount', 18, 2)->default(0);
            $t->date('realization_date')->nullable();
            $t->string('funding_source')->nullable();
            $t->text('notes')->nullable();
            $t->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('verified_at')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('initiative_budgets');
    }
};
