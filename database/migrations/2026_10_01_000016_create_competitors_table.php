<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitors', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('category', 50)->nullable();
            $t->unsignedInteger('room_count')->nullable();
            $t->decimal('distance_km', 6, 2)->nullable();
            $t->string('data_period', 50)->nullable();
            $t->unsignedTinyInteger('rank')->nullable();
            $t->unsignedInteger('room_available')->nullable();
            $t->unsignedInteger('room_sold')->nullable();
            $t->decimal('occupancy', 6, 2)->nullable();
            $t->decimal('arr', 15, 2)->nullable();
            $t->decimal('revpar', 15, 2)->nullable();
            $t->decimal('rgi', 6, 2)->nullable();
            $t->decimal('mpi', 6, 2)->nullable();
            $t->decimal('ari', 6, 2)->nullable();
            $t->decimal('rating_google', 4, 1)->nullable();
            $t->decimal('rating_tripadvisor', 4, 1)->nullable();
            $t->decimal('rating_traveloka', 4, 1)->nullable();
            $t->decimal('rating_booking', 4, 1)->nullable();
            $t->decimal('rating_tiket', 4, 1)->nullable();
            $t->boolean('is_self')->default(false);
            $t->text('strengths')->nullable();
            $t->text('weaknesses')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitors');
    }
};
