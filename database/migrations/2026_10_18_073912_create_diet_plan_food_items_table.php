<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('diet_plan_food_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_id')->constrained('diet_plan_meals')->cascadeOnDelete();
            $table->string('food_name', 150);
            $table->string('quantity', 80);
            $table->integer('calories')->nullable();
            $table->decimal('protein_g', 6, 1)->nullable();
            $table->decimal('carbs_g', 6, 1)->nullable();
            $table->decimal('fat_g', 6, 1)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diet_plan_food_items');
    }
};
