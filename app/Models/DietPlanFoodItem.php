<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DietPlanFoodItem extends Model
{
    protected $fillable = [
        'meal_id', 'food_name', 'quantity', 'calories', 'protein_g', 'carbs_g', 'fat_g',
    ];
    protected $casts = ['protein_g' => 'decimal:1', 'carbs_g' => 'decimal:1', 'fat_g' => 'decimal:1'];

    public function meal() { return $this->belongsTo(DietPlanMeal::class, 'meal_id'); }
}
