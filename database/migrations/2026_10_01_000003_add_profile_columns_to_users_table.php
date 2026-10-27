<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('job_title')->nullable()->after('email');
            $t->string('department', 60)->nullable()->after('job_title');
            $t->string('phone', 50)->nullable()->after('department');
            $t->boolean('is_active')->default(true)->after('phone');
            $t->boolean('must_change_password')->default(true)->after('is_active');
            $t->timestamp('password_changed_at')->nullable()->after('must_change_password');
            $t->timestamp('last_login_at')->nullable()->after('password_changed_at');
            $t->text('app_authentication_secret')->nullable();
            $t->text('app_authentication_recovery_codes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['job_title', 'department', 'phone', 'is_active', 'must_change_password',
                'password_changed_at', 'last_login_at', 'app_authentication_secret', 'app_authentication_recovery_codes']);
        });
    }
};
