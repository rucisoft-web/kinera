<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategic_choices', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('code', 40)->nullable();
            $t->foreignId('strategic_theme_id')->nullable()->constrained()->nullOnDelete();
            $t->string('title');
            $t->string('choice_type', 30)->nullable();
            $t->text('description')->nullable();
            $t->text('rationale')->nullable();
            $t->string('status', 20)->default('dipilih');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategic_choices');
    }
};
