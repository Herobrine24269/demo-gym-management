<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'member_id', 'date', 'checked_in_at', 'checked_out_at',
        'marked_by', 'note', 'is_corrected', 'corrected_by', 'corrected_at',
    ];

    protected $casts = [
        'date'         => 'date',
        'is_corrected' => 'boolean',
        'corrected_at' => 'datetime',
    ];

    public function member()   { return $this->belongsTo(Member::class); }
    public function marker()   { return $this->belongsTo(User::class, 'marked_by'); }
    public function corrector(){ return $this->belongsTo(User::class, 'corrected_by'); }

    public function scopeToday($q)  { return $q->whereDate('date', today()); }
    public function scopeForDate($q, $date) { return $q->whereDate('date', $date); }
}
