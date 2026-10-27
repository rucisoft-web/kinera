<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atman_profiles', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->text('vision')->nullable();
            $t->text('mission')->nullable();
            $t->text('purpose')->nullable();
            $t->string('tagline')->nullable();
            $t->text('brand_promise')->nullable();
            $t->text('value_proposition')->nullable();
            $t->text('culture_statement')->nullable();
            $t->text('history')->nullable();
            $t->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('approved_at')->nullable();
            $t->timestamps();
            $t->unique(['company_id', 'analysis_period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atman_profiles');
    }
};
