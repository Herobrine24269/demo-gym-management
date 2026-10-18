<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonOrder extends Model
{
    protected $fillable = ['member_id', 'addon_id', 'qty', 'unit_price', 'payment_id'];
    protected $casts = ['unit_price' => 'decimal:2'];

    public function member()  { return $this->belongsTo(Member::class); }
    public function addon()   { return $this->belongsTo(Addon::class); }
    public function payment() { return $this->belongsTo(Payment::class); }
}
