<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $t->string('event', 30);
            $t->string('auditable_type');
            $t->unsignedBigInteger('auditable_id')->nullable();
            $t->string('auditable_label')->nullable();
            $t->json('old_values')->nullable();
            $t->json('new_values')->nullable();
            $t->string('url', 500)->nullable();
            $t->string('ip_address', 45)->nullable();
            $t->string('user_agent', 500)->nullable();
            $t->timestamp('created_at')->nullable();
            $t->index(['auditable_type', 'auditable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
