<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutPlan extends Model
{
    protected $fillable = ['trainer_id', 'member_id', 'title', 'description', 'weeks', 'status'];

    public function trainer() { return $this->belongsTo(Trainer::class); }
    public function member()  { return $this->belongsTo(Member::class); }
    public function logs()    { return $this->hasMany(WorkoutLog::class); }
}
