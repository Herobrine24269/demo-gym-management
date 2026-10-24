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

    public function scopePublished($q)
    {
        return $q->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeDraft($q)
    {
        return $q->where('status', 'draft');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->featured_image) {
            return null;
        }
        if (str_starts_with($this->featured_image, 'http://') || str_starts_with($this->featured_image, 'https://')) {
            return $this->featured_image;
        }
        return asset('storage/' . $this->featured_image);
    }

    public function getReadTimeAttribute(): int
    {
        if ($this->read_minutes && $this->read_minutes > 0) {
            return $this->read_minutes;
        }
        $words = str_word_count(strip_tags($this->body ?? ''));
        return max(1, (int) ceil($words / 200));
    }
}

