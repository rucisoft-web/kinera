<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategic_initiatives', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('code', 40)->nullable();
            $t->string('title');
            $t->text('description')->nullable();
            $t->foreignId('strategic_theme_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $t->date('start_date')->nullable();
            $t->date('end_date')->nullable();
            $t->string('status', 20)->default('direncanakan');
            $t->unsignedTinyInteger('impact')->default(3);
            $t->unsignedTinyInteger('urgency')->default(3);
            $t->unsignedTinyInteger('risk')->default(3);
            $t->unsignedTinyInteger('budget_level')->default(3);
            $t->decimal('budget_planned', 18, 2)->default(0);
            $t->decimal('progress', 5, 2)->default(0);
            $t->text('expected_outcome')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategic_initiatives');
    }
};
