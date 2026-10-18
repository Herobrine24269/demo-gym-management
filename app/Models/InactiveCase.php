<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InactiveCase extends Model
{
    protected $fillable = [
        'member_id', 'days_absent', 'last_attendance_date',
        'priority', 'status', 'resolved_at',
    ];

    protected $casts = [
        'last_attendance_date' => 'date',
        'resolved_at'          => 'datetime',
    ];

    public function member() { return $this->belongsTo(Member::class); }

    public function scopeOpen($q)   { return $q->where('status', 'open'); }
    public function scopeByPriority($q, $p) { return $q->where('priority', $p); }

    public static function priorityFor(int $daysAbsent): string
    {
        return match(true) {
            $daysAbsent >= 21 => 'critical',
            $daysAbsent >= 15 => 'urgent',
            default           => 'watch',
        };
    }
}
