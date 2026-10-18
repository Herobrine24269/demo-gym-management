<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalTrainingSession extends Model
{
    protected $fillable = [
        'member_id', 'trainer_id', 'package_name', 'total_sessions',
        'sessions_done', 'price', 'payment_id', 'status', 'started_at',
    ];
    protected $casts = ['started_at' => 'date', 'price' => 'decimal:2'];

    public function member()  { return $this->belongsTo(Member::class); }
    public function trainer() { return $this->belongsTo(Trainer::class); }
    public function payment() { return $this->belongsTo(Payment::class); }

    public function sessionsRemaining(): int { return $this->total_sessions - $this->sessions_done; }
}
