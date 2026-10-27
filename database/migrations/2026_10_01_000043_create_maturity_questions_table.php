<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maturity_questions', function (Blueprint $t) {
            $t->id();
            $t->string('phase', 20);
            $t->string('dimension');
            $t->text('question');
            $t->text('guidance')->nullable();
            $t->decimal('weight', 5, 2)->default(1);
            $t->unsignedSmallInteger('sort')->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maturity_questions');
    }
};
