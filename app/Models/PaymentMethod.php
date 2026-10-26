<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'type', 'is_enabled', 'account_name', 'account_number', 'phone',
        'bank_name', 'iban', 'branch', 'qr_image_path', 'instructions', 'updated_by',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function submissions()
    {
        return $this->hasMany(PaymentSubmission::class);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function getDisplayNameAttribute(): string
    {
        return match ($this->type) {
            'easypaisa'     => 'Easypaisa',
            'jazzcash'      => 'JazzCash',
            'bank_transfer' => 'Bank Transfer',
            default         => ucwords(str_replace('_', ' ', $this->type)),
        };
    }

    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'easypaisa'     => 'account_balance_wallet',
            'jazzcash'      => 'payments',
            'bank_transfer' => 'account_balance',
            default         => 'credit_card',
        };
    }

    public function getQrImageUrlAttribute(): ?string
    {
        if (!$this->qr_image_path) {
            return null;
        }
        if (str_starts_with($this->qr_image_path, 'http://') || str_starts_with($this->qr_image_path, 'https://')) {
            return $this->qr_image_path;
        }
        return asset('storage/' . $this->qr_image_path);
    }
}
