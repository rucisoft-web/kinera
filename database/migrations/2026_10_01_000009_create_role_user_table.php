<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_user', function (Blueprint $t) {
            $t->id();
            $t->foreignId('role_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('company_id')->nullable()->constrained()->cascadeOnDelete();
            $t->timestamps();
            $t->unique(['role_id', 'user_id', 'company_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
