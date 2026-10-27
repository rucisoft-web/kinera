<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tows_strategies', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('code', 40)->nullable();
            $t->string('type', 2);
            $t->string('title');
            $t->text('description')->nullable();
            $t->unsignedTinyInteger('priority')->default(3);
            $t->string('status', 20)->default('diusulkan');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tows_strategies');
    }
};
