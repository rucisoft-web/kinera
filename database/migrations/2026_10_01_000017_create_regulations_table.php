<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regulations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->string('issuer')->nullable();
            $t->string('reference_no')->nullable();
            $t->string('category', 40)->nullable();
            $t->date('effective_date')->nullable();
            $t->text('description')->nullable();
            $t->text('impact')->nullable();
            $t->string('compliance_status', 20)->default('dipantau');
            $t->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $t->date('due_date')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regulations');
    }
};
