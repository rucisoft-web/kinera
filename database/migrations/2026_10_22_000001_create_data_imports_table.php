<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $t) {
            $t->boolean('is_demo')->default(false)->after('is_active');
        });

        Schema::create('data_imports', function (Blueprint $t) {
            $t->id();
            $t->foreignId('company_id')->constrained()->cascadeOnDelete();
            $t->foreignId('analysis_period_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('file_name');
            $t->string('file_path')->nullable();
            $t->string('status', 20)->default('selesai');
            $t->json('options')->nullable();
            $t->json('summary')->nullable();
            $t->json('issues')->nullable();
            $t->unsignedInteger('rows_ok')->default(0);
            $t->unsignedInteger('rows_failed')->default(0);
            $t->timestamp('rolled_back_at')->nullable();
            $t->foreignId('rolled_back_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        // Jejak perubahan tiap import, dipakai untuk membatalkan (rollback).
        Schema::create('data_import_changes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('data_import_id')->constrained()->cascadeOnDelete();
            $t->string('model_type');
            $t->unsignedBigInteger('model_id');
            $t->string('action', 10);
            $t->json('old_values')->nullable();
            $t->timestamp('created_at')->nullable();
            $t->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_import_changes');
        Schema::dropIfExists('data_imports');
        Schema::table('companies', fn (Blueprint $t) => $t->dropColumn('is_demo'));
    }
};
