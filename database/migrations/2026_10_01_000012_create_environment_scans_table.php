<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('environment_scans', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->string('code', 40)->nullable();
            $t->string('title');
            $t->date('scan_date');
            $t->string('scope', 20)->default('eksternal');
            $t->string('source')->nullable();
            $t->text('summary')->nullable();
            $t->text('implications')->nullable();
            $t->string('status', 20)->default('aktif');
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('environment_scans');
    }
};
