<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerAssignment extends Model
{
    protected $fillable = ['trainer_id', 'member_id', 'assigned_at', 'ended_at'];
    protected $casts = ['assigned_at' => 'date', 'ended_at' => 'date'];

    public function trainer() { return $this->belongsTo(Trainer::class); }
    public function member()  { return $this->belongsTo(Member::class); }
}
