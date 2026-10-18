<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Streak extends Model
{
    protected $fillable = ['member_id', 'current_streak', 'longest_streak', 'last_seen_date'];
    protected $casts = ['last_seen_date' => 'date'];

    public function member() { return $this->belongsTo(Member::class); }
}
