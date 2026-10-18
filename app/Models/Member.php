<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'member_code', 'gender', 'dob',
        'address', 'emergency_contact', 'health_notes', 'joined_at',
    ];

    protected $casts = ['dob' => 'date', 'joined_at' => 'date'];

    /* ── Relationships ────────────────────────── */
    public function user()           { return $this->belongsTo(User::class); }
    public function memberships()    { return $this->hasMany(Membership::class); }
    public function activeMembership() {
        return $this->hasOne(Membership::class)->where('status', 'active')->latestOfMany();
    }
    public function attendance()     { return $this->hasMany(Attendance::class); }
    public function streak()         { return $this->hasOne(Streak::class); }
    public function trainerAssignments() { return $this->hasMany(TrainerAssignment::class); }
    public function currentTrainer() {
        return $this->hasOneThrough(Trainer::class, TrainerAssignment::class, 'member_id', 'id', 'id', 'trainer_id')
            ->whereNull('trainer_assignments.ended_at');
    }
    public function workoutPlans()   { return $this->hasMany(WorkoutPlan::class); }
    public function payments()       { return $this->hasMany(Payment::class); }
    public function followUps()      { return $this->hasMany(FollowUp::class); }
    public function inactiveCase()   { return $this->hasOne(InactiveCase::class); }
    public function dietAssignments(){ return $this->hasMany(MemberDietAssignment::class); }
    public function activeDietPlan() {
        return $this->hasOneThrough(DietPlan::class, MemberDietAssignment::class, 'member_id', 'id', 'id', 'diet_plan_id')
            ->whereNull('member_diet_assignments.ended_at');
    }
    public function ptSessions()     { return $this->hasMany(PersonalTrainingSession::class); }
    public function addonOrders()    { return $this->hasMany(AddonOrder::class); }

    /* ── Accessors ────────────────────────────── */
    public function getNameAttribute()  { return $this->user->name ?? ''; }
    public function getEmailAttribute() { return $this->user->email ?? ''; }
    public function getPhoneAttribute() { return $this->user->phone ?? ''; }
    public function getAvatarAttribute(){ return $this->user->avatar ?? null; }

    public function initials(): string
    {
        $words = explode(' ', $this->user->name ?? '?');
        return strtoupper(
            collect($words)->take(2)->map(fn($w) => $w[0] ?? '')->implode('')
        );
    }

    /* ── Days since last visit ────────────────── */
    public function daysAbsent(): int
    {
        $last = $this->attendance()->latest('date')->value('date');
        return $last ? now()->diffInDays($last) : 9999;
    }
}
