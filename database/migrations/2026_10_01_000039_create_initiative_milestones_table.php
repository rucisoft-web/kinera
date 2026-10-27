<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('initiative_milestones', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->foreignId('strategic_initiative_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->date('due_date');
            $t->decimal('weight', 6, 2)->default(1);
            $t->boolean('is_achieved')->default(false);
            $t->date('achieved_at')->nullable();
            $t->text('evidence')->nullable();
            $t->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('initiative_milestones');
    }
};
