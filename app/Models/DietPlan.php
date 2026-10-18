<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DietPlan extends Model
{
    protected $fillable = [
        'created_by', 'title', 'goal',
        'total_calories', 'total_protein_g', 'total_carbs_g', 'total_fat_g', 'is_public',
    ];
    protected $casts = ['is_public' => 'boolean'];

    public function creator()     { return $this->belongsTo(User::class, 'created_by'); }
    public function meals()       { return $this->hasMany(DietPlanMeal::class)->orderBy('sort_order'); }
    public function assignments() { return $this->hasMany(MemberDietAssignment::class); }
}
