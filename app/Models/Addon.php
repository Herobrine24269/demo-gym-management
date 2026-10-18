<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $fillable = ['name', 'type', 'price', 'stock_qty', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'price' => 'decimal:2'];

    public function orders() { return $this->hasMany(AddonOrder::class); }
}
