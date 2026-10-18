<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    protected $fillable = ['name', 'duration_days', 'price', 'features', 'is_active'];
    protected $casts = ['features' => 'array', 'is_active' => 'boolean', 'price' => 'decimal:2'];

    public function memberships() { return $this->hasMany(Membership::class, 'plan_id'); }
}
