<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('initiative_activities', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->foreignId('strategic_initiative_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->text('description')->nullable();
            $t->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $t->date('start_date')->nullable();
            $t->date('due_date')->nullable();
            $t->decimal('weight', 6, 2)->default(1);
            $t->decimal('progress', 5, 2)->default(0);
            $t->string('status', 20)->default('belum');
            $t->date('completed_at')->nullable();
            $t->text('last_update_note')->nullable();
            $t->timestamp('progress_updated_at')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('initiative_activities');
    }
};
