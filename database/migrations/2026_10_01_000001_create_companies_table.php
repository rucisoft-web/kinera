<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $t) {
            $t->id();
            $t->string('code', 30)->unique();
            $t->string('name');
            $t->string('legal_name')->nullable();
            $t->string('industry', 100)->nullable();
            $t->string('address')->nullable();
            $t->string('city', 100)->nullable();
            $t->string('phone', 50)->nullable();
            $t->string('email')->nullable();
            $t->string('website')->nullable();
            $t->unsignedInteger('room_count')->nullable();
            $t->text('description')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
