<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_reviews', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('code', 40)->nullable();
            $t->string('title');
            $t->string('review_type', 30);
            $t->date('review_date');
            $t->string('period_label')->nullable();
            $t->string('chair')->nullable();
            $t->text('participants')->nullable();
            $t->string('venue')->nullable();
            $t->text('summary')->nullable();
            $t->text('decisions')->nullable();
            $t->text('follow_up')->nullable();
            $t->string('status', 20)->default('final');
            $t->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
