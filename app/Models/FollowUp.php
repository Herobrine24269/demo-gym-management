<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = ['member_id', 'called_by', 'outcome', 'notes', 'followed_up_at'];
    protected $casts = ['followed_up_at' => 'datetime'];

    public function member() { return $this->belongsTo(Member::class); }
    public function caller() { return $this->belongsTo(User::class, 'called_by'); }
}
