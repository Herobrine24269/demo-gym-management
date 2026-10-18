<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'author_id', 'title', 'slug', 'excerpt',
        'body', 'featured_image', 'status', 'published_at', 'read_minutes',
    ];
    protected $casts = ['published_at' => 'datetime'];

    public function category() { return $this->belongsTo(PostCategory::class); }
    public function author()   { return $this->belongsTo(User::class, 'author_id'); }

    public function scopePublished($q) { return $q->where('status', 'published')->whereNotNull('published_at'); }
}
