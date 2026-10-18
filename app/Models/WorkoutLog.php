<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutLog extends Model
{
    protected $fillable = ['workout_plan_id', 'member_id', 'logged_at', 'exercises', 'notes'];
    protected $casts = ['logged_at' => 'date', 'exercises' => 'array'];

    public function plan()   { return $this->belongsTo(WorkoutPlan::class, 'workout_plan_id'); }
    public function member() { return $this->belongsTo(Member::class); }
}
