<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strategic_issue_stakeholder', function (Blueprint $t) {
            $t->foreignId('strategic_issue_id')->constrained()->cascadeOnDelete();
            $t->foreignId('stakeholder_id')->constrained()->cascadeOnDelete();
            $t->primary(['strategic_issue_id', 'stakeholder_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strategic_issue_stakeholder');
    }
};
