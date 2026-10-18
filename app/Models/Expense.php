<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['category', 'amount', 'description', 'incurred_at', 'recorded_by'];
    protected $casts = ['incurred_at' => 'date', 'amount' => 'decimal:2'];

    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }

    public function scopeThisMonth($q) { return $q->whereMonth('incurred_at', now()->month)->whereYear('incurred_at', now()->year); }
}
