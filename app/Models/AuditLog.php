<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'user_id', 'action', 'target_type', 'target_id', 'before', 'after', 'ip',
    ];
    protected $casts = ['before' => 'array', 'after' => 'array', 'created_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }

    public static function record(string $action, Model $target, ?array $before = null, ?array $after = null): void
    {
        static::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'target_type' => class_basename($target),
            'target_id'   => $target->getKey(),
            'before'      => $before,
            'after'       => $after,
            'ip'          => request()->ip(),
        ]);
    }
}
