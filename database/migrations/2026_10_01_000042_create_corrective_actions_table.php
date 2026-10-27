<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corrective_actions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->foreignId('finding_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->text('description')->nullable();
            $t->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $t->date('due_date')->nullable();
            $t->string('status', 20)->default('belum');
            $t->unsignedTinyInteger('progress')->default(0);
            $t->date('completed_at')->nullable();
            $t->text('result_note')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corrective_actions');
    }
};
