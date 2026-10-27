<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analysis_periods', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->string('code', 30);
            $t->string('name');
            $t->unsignedSmallInteger('year');
            $t->date('start_date');
            $t->date('end_date');
            $t->string('status', 20)->default('perencanaan');
            $t->boolean('is_active')->default(false);
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->unique(['company_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analysis_periods');
    }
};
