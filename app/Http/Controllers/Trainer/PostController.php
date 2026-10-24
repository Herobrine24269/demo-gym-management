<?php

namespace App\Http\Controllers\Trainer;

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
     * Display a listing of posts from the trainer dashboard.
     */
    public function index(Request $request)
    {
        $query = Post::with(['category', 'author'])->latest();

        // Filter: 'my' (only current trainer's posts) vs 'all' (all facility posts)
        $scope = $request->input('scope', 'my');
        if ($scope === 'my') {
            $query->where('author_id', auth()->id());
        }

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
            'my_total'     => Post::where('author_id', auth()->id())->count(),
            'my_published' => Post::where('author_id', auth()->id())->published()->count(),
            'my_drafts'    => Post::where('author_id', auth()->id())->where('status', 'draft')->count(),
            'all_total'    => Post::count(),
        ];

        return view('trainer.posts.index', compact('posts', 'categories', 'stats', 'scope'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $categories = PostCategory::orderBy('name')->get();
        return view('trainer.posts.create', compact('categories'));
    }

    /**
     * Store a newly created post by the trainer.
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

        // Category resolution
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
                ['slug' => 'fitness-tips'],
                ['name' => 'Fitness Tips']
            );
            $categoryId = $fallbackCat->id;
        }

        // Unique slug
        $slug = $this->generateUniqueSlug($data['title']);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        } elseif (!empty($data['featured_image_url'])) {
            $imagePath = $data['featured_image_url'];
        }

        // Compute read minutes
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

        AuditLog::record('trainer_post_created', $post, null, $post->toArray());

        return redirect()->route('trainer.posts.index')->with('success', 'Your post "' . $post->title . '" has been created!');
    }

    /**
     * Display post preview.
     */
    public function show(Post $post)
    {
        $post->load(['category', 'author']);
        return view('trainer.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the trainer's post.
     */
    public function edit(Post $post)
    {
        $this->authorizePostAccess($post);

        $categories = PostCategory::orderBy('name')->get();
        return view('trainer.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the trainer's post.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorizePostAccess($post);

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

        AuditLog::record('trainer_post_updated', $post, $before, $post->toArray());

        return redirect()->route('trainer.posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the trainer's post.
     */
    public function destroy(Post $post)
    {
        $this->authorizePostAccess($post);

        $before = $post->toArray();
        $title = $post->title;

        if ($post->featured_image && !str_starts_with($post->featured_image, 'http')) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        AuditLog::record('trainer_post_deleted', $post, $before, null);

        return redirect()->route('trainer.posts.index')->with('success', 'Post "' . $title . '" deleted.');
    }

    /**
     * Toggle post publish status.
     */
    public function publish(Post $post)
    {
        $this->authorizePostAccess($post);

        $before = $post->toArray();

        if ($post->status === 'published') {
            $post->update([
                'status'       => 'draft',
                'published_at' => null,
            ]);
            $msg = 'Post set to draft.';
        } else {
            $post->update([
                'status'       => 'published',
                'published_at' => now(),
            ]);
            $msg = 'Post published on visitor frontend.';
        }

        AuditLog::record('trainer_post_status_toggled', $post, $before, $post->toArray());

        return back()->with('success', $msg);
    }

    /**
     * Ensure trainer is author of the post or is an admin.
     */
    protected function authorizePostAccess(Post $post): void
    {
        if ($post->author_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized. You may only edit or delete your own posts.');
        }
    }

    /**
     * Unique slug helper.
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
