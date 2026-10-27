<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_actuals', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->foreignId('kpi_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('month');
            $t->decimal('actual', 18, 4);
            $t->text('evidence')->nullable();
            $t->string('evidence_path')->nullable();
            $t->text('notes')->nullable();
            $t->boolean('is_projection')->default(false);
            $t->string('status', 20)->default('draf');
            $t->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('submitted_at')->nullable();
            $t->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('verified_at')->nullable();
            $t->text('verification_note')->nullable();
            $t->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('approved_at')->nullable();
            $t->foreignId('returned_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('returned_at')->nullable();
            $t->text('return_note')->nullable();
            $t->timestamps();
            $t->unique(['kpi_id', 'month']);
            $t->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_actuals');
    }
};
