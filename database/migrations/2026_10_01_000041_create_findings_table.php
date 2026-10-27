<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('findings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('code', 40)->nullable();
            $t->string('title');
            $t->text('description')->nullable();
            $t->string('source', 20)->default('tinjauan');
            $t->foreignId('performance_review_id')->nullable()->constrained()->nullOnDelete();
            $t->string('perspective', 20)->nullable();
            $t->string('severity', 20)->default('sedang');
            $t->decimal('amount', 18, 2)->nullable();
            $t->foreignId('kpi_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('strategic_initiative_id')->nullable()->constrained()->nullOnDelete();
            $t->string('status', 30)->default('terbuka');
            $t->foreignId('raised_by')->nullable()->constrained('users')->nullOnDelete();
            $t->date('raised_at')->nullable();
            $t->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('closed_at')->nullable();
            $t->text('closure_note')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('findings');
    }
};
