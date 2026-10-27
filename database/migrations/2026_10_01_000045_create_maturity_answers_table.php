<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maturity_answers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('maturity_assessment_id')->constrained()->cascadeOnDelete();
            $t->foreignId('maturity_question_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('score')->default(1);
            $t->text('evidence')->nullable();
            $t->timestamps();
            $t->unique(['maturity_assessment_id', 'maturity_question_id'], 'maturity_answer_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maturity_answers');
    }
};
