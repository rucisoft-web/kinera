<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capabilities', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('category', 40)->nullable();
            $t->text('description')->nullable();
            $t->unsignedTinyInteger('current_level')->default(3);
            $t->unsignedTinyInteger('target_level')->default(4);
            $t->boolean('is_core')->default(false);
            $t->text('gap_action')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capabilities');
    }
};
