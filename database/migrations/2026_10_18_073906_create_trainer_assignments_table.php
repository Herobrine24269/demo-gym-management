<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trainer_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->date('assigned_at');
            $table->date('ended_at')->nullable();
            $table->timestamps();

            $table->index(['trainer_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_assignments');
    }
};
