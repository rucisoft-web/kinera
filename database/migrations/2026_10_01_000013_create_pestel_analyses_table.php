<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pestel_analyses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('factor', 20);
            $t->string('title');
            $t->text('description')->nullable();
            $t->string('impact_type', 20)->default('ancaman');
            $t->unsignedTinyInteger('impact_level')->default(3);
            $t->unsignedTinyInteger('likelihood')->default(3);
            $t->string('trend', 20)->default('stabil');
            $t->text('response')->nullable();
            $t->string('source')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pestel_analyses');
    }
};
