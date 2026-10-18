<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenewalOrder extends Model
{
    protected $fillable = ['member_id', 'old_membership_id', 'new_membership_id', 'processed_by'];

    public function member()       { return $this->belongsTo(Member::class); }
    public function oldMembership(){ return $this->belongsTo(Membership::class, 'old_membership_id'); }
    public function newMembership(){ return $this->belongsTo(Membership::class, 'new_membership_id'); }
    public function processor()    { return $this->belongsTo(User::class, 'processed_by'); }
}
