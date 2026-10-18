<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'specialization', 'bio', 'salary', 'hired_at', 'is_active',
    ];

    protected $casts = ['hired_at' => 'date', 'is_active' => 'boolean', 'salary' => 'decimal:2'];

    public function user()          { return $this->belongsTo(User::class); }
    public function assignments()   { return $this->hasMany(TrainerAssignment::class); }
    public function activeMembers() {
        return $this->hasMany(TrainerAssignment::class)->whereNull('ended_at');
    }
    public function workoutPlans()  { return $this->hasMany(WorkoutPlan::class); }
    public function ptSessions()    { return $this->hasMany(PersonalTrainingSession::class); }

    public function getNameAttribute()  { return $this->user->name ?? ''; }
    public function getEmailAttribute() { return $this->user->email ?? ''; }
    public function getPhoneAttribute() { return $this->user->phone ?? ''; }

    public function initials(): string
    {
        $words = explode(' ', $this->user->name ?? '?');
        return strtoupper(collect($words)->take(2)->map(fn($w) => $w[0] ?? '')->implode(''));
    }
}
