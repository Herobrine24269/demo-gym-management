<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('diet_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->string('title', 150);
            $table->enum('goal', ['weight_loss', 'muscle_gain', 'maintenance', 'performance'])->default('maintenance');
            $table->integer('total_calories')->nullable();
            $table->integer('total_protein_g')->nullable();
            $table->integer('total_carbs_g')->nullable();
            $table->integer('total_fat_g')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diet_plans');
    }
};
