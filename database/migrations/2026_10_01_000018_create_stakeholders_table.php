<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stakeholders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('type', 20)->default('eksternal');
            $t->string('category', 50)->nullable();
            $t->unsignedTinyInteger('interest')->default(3);
            $t->unsignedTinyInteger('influence')->default(3);
            $t->text('expectations')->nullable();
            $t->text('engagement_strategy')->nullable();
            $t->string('contact_person')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stakeholders');
    }
};
