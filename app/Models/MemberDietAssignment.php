<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDietAssignment extends Model
{
    protected $fillable = ['member_id', 'diet_plan_id', 'assigned_by', 'assigned_at', 'ended_at'];
    protected $casts = ['assigned_at' => 'date', 'ended_at' => 'date'];

    public function member()   { return $this->belongsTo(Member::class); }
    public function dietPlan() { return $this->belongsTo(DietPlan::class); }
    public function assigner() { return $this->belongsTo(User::class, 'assigned_by'); }
}
