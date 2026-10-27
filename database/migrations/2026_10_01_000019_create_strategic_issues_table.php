<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategic_issues', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('code', 40)->nullable();
            $t->string('title');
            $t->text('description')->nullable();
            $t->string('perspective', 20)->nullable();
            $t->unsignedTinyInteger('urgency')->default(3);
            $t->unsignedTinyInteger('impact')->default(3);
            $t->string('status', 20)->default('terbuka');
            $t->string('source')->nullable();
            $t->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategic_issues');
    }
};
