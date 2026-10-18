<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DietPlanMeal extends Model
{
    protected $fillable = ['diet_plan_id', 'meal_name', 'time_suggestion', 'sort_order'];

    public function plan()      { return $this->belongsTo(DietPlan::class, 'diet_plan_id'); }
    public function foodItems() { return $this->hasMany(DietPlanFoodItem::class, 'meal_id'); }
}
