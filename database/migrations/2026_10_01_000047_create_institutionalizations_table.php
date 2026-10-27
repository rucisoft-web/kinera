<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutionalizations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->string('mechanism_type', 30);
            $t->text('description')->nullable();
            $t->string('frequency', 30)->nullable();
            $t->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('status', 20)->default('rencana');
            $t->text('evidence')->nullable();
            $t->date('started_at')->nullable();
            $t->date('next_review_date')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutionalizations');
    }
};
