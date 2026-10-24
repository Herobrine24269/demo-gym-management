<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of posts in the admin panel.
     */
    public function index(Request $request)
    {
        $query = Post::with(['category', 'author'])->latest();

        // Search
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            if (in_array($status, ['published', 'draft'])) {
                $query->where('status', $status);
            }
        }

        // Category filter
        if ($catId = $request->input('category_id')) {
            $query->where('category_id', $catId);
        }

        $posts = $query->paginate(15)->withQueryString();
        $categories = PostCategory::orderBy('name')->get();

        $stats = [
            'total'     => Post::count(),
            'published' => Post::published()->count(),
            'draft'     => Post::where('status', 'draft')->count(),
        ];

        return view('admin.posts.index', compact('posts', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $categories = PostCategory::orderBy('name')->get();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'category_id'        => 'nullable|exists:post_categories,id',
            'new_category'       => 'nullable|string|max:100',
            'excerpt'            => 'nullable|string|max:500',
            'body'               => 'required|string',
            'featured_image'     => 'nullable|image|max:5120',
            'featured_image_url' => 'nullable|url|max:255',
            'status'             => 'required|in:draft,published',
            'read_minutes'       => 'nullable|integer|min:1',
        ]);

        // Handle category (new or existing)
        $categoryId = $data['category_id'] ?? null;
        if (!empty($data['new_category'])) {
            $cat = PostCategory::firstOrCreate(
                ['slug' => Str::slug($data['new_category'])],
                ['name' => trim($data['new_category'])]
            );
            $categoryId = $cat->id;
        }

        if (!$categoryId) {
            $fallbackCat = PostCategory::firstOrCreate(
                ['slug' => 'general'],
                ['name' => 'General']
            );
            $categoryId = $fallbackCat->id;
        }

        // Unique slug generation
        $slug = $this->generateUniqueSlug($data['title']);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        } elseif (!empty($data['featured_image_url'])) {
            $imagePath = $data['featured_image_url'];
        }

        // Compute read minutes if empty
        $readMinutes = $data['read_minutes'] ?? null;
        if (!$readMinutes) {
            $words = str_word_count(strip_tags($data['body']));
            $readMinutes = max(1, (int) ceil($words / 200));
        }

        $post = Post::create([
            'category_id'    => $categoryId,
            'author_id'      => auth()->id(),
            'title'          => $data['title'],
            'slug'           => $slug,
            'excerpt'        => $data['excerpt'] ?? Str::limit(strip_tags($data['body']), 140),
            'body'           => $data['body'],
            'featured_image' => $imagePath,
            'status'         => $data['status'],
            'published_at'   => $data['status'] === 'published' ? now() : null,
            'read_minutes'   => $readMinutes,
        ]);

        AuditLog::record('post_created', $post, null, $post->toArray());

        return redirect()->route('admin.posts.index')->with('success', 'Post "' . $post->title . '" created successfully.');
    }

    /**
     * Display the specified post preview.
     */
    public function show(Post $post)
    {
        $post->load(['category', 'author']);
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        $categories = PostCategory::orderBy('name')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'category_id'        => 'nullable|exists:post_categories,id',
            'new_category'       => 'nullable|string|max:100',
            'excerpt'            => 'nullable|string|max:500',
            'body'               => 'required|string',
            'featured_image'     => 'nullable|image|max:5120',
            'featured_image_url' => 'nullable|url|max:255',
            'status'             => 'required|in:draft,published',
            'read_minutes'       => 'nullable|integer|min:1',
        ]);

        $before = $post->toArray();

        // Handle category
        $categoryId = $data['category_id'] ?? $post->category_id;
        if (!empty($data['new_category'])) {
            $cat = PostCategory::firstOrCreate(
                ['slug' => Str::slug($data['new_category'])],
                ['name' => trim($data['new_category'])]
            );
            $categoryId = $cat->id;
        }

        // Image upload
        $imagePath = $post->featured_image;
        if ($request->hasFile('featured_image')) {
            // Delete old file if local
            if ($post->featured_image && !str_starts_with($post->featured_image, 'http')) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        } elseif (!empty($data['featured_image_url'])) {
            $imagePath = $data['featured_image_url'];
        }

        // Slug update if title changed
        $slug = $post->slug;
        if ($post->title !== $data['title']) {
            $slug = $this->generateUniqueSlug($data['title'], $post->id);
        }

        // Published status handling
        $publishedAt = $post->published_at;
        if ($data['status'] === 'published' && !$publishedAt) {
            $publishedAt = now();
        } elseif ($data['status'] === 'draft') {
            $publishedAt = null;
        }

        // Compute read minutes
        $readMinutes = $data['read_minutes'] ?? null;
        if (!$readMinutes) {
            $words = str_word_count(strip_tags($data['body']));
            $readMinutes = max(1, (int) ceil($words / 200));
        }

        $post->update([
            'category_id'    => $categoryId,
            'title'          => $data['title'],
            'slug'           => $slug,
            'excerpt'        => $data['excerpt'] ?? Str::limit(strip_tags($data['body']), 140),
            'body'           => $data['body'],
            'featured_image' => $imagePath,
            'status'         => $data['status'],
            'published_at'   => $publishedAt,
            'read_minutes'   => $readMinutes,
        ]);

        AuditLog::record('post_updated', $post, $before, $post->toArray());

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        $before = $post->toArray();
        $title = $post->title;

        // Delete image if local
        if ($post->featured_image && !str_starts_with($post->featured_image, 'http')) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        AuditLog::record('post_deleted', $post, $before, null);

        return redirect()->route('admin.posts.index')->with('success', 'Post "' . $title . '" deleted.');
    }

    /**
     * Quick publish / unpublish toggle.
     */
    public function publish(Post $post)
    {
        $before = $post->toArray();

        if ($post->status === 'published') {
            $post->update([
                'status'       => 'draft',
                'published_at' => null,
            ]);
            $msg = 'Post moved to drafts.';
        } else {
            $post->update([
                'status'       => 'published',
                'published_at' => now(),
            ]);
            $msg = 'Post published successfully.';
        }

        AuditLog::record('post_status_toggled', $post, $before, $post->toArray());

        return back()->with('success', $msg);
    }

    /**
     * Helper to generate unique slug.
     */
    protected function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 1;

        while (Post::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-" . (++$counter);
        }

        return $slug;
    }
}
