<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maturity_assessments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('code', 40)->nullable();
            $t->string('title');
            $t->date('assessment_date');
            $t->foreignId('assessor_id')->nullable()->constrained('users')->nullOnDelete();
            $t->decimal('overall_score', 4, 2)->nullable();
            $t->unsignedTinyInteger('level')->nullable();
            $t->string('status', 20)->default('draf');
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maturity_assessments');
    }
};
