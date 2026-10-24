<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of published posts on the visitor frontend.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));
        $categorySlug = $request->input('category');
        $authorId = $request->input('author');

        $query = Post::published()
            ->with(['category', 'author.trainer'])
            ->latest('published_at');

        // Search query
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        // Category filter
        $activeCategory = null;
        if ($categorySlug) {
            $activeCategory = PostCategory::where('slug', $categorySlug)->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        // Author filter
        if ($authorId) {
            $query->where('author_id', $authorId);
        }

        // If no filters active and on first page, grab the top post for the hero highlight
        $featuredPost = null;
        if ($search === '' && !$categorySlug && !$authorId && $request->input('page', 1) == 1) {
            $allPublished = (clone $query)->get();
            $featuredPost = $allPublished->first();
            $posts = $query->where('id', '!=', $featuredPost?->id ?? 0)->paginate(9)->withQueryString();
        } else {
            $posts = $query->paginate(9)->withQueryString();
        }

        // Categories with published posts count
        $categories = PostCategory::whereHas('posts', function ($q) {
            $q->published();
        })->withCount(['posts' => function ($q) {
            $q->published();
        }])->get();

        $totalPublishedCount = Post::published()->count();

        return view('posts.index', compact(
            'posts',
            'featuredPost',
            'categories',
            'activeCategory',
            'search',
            'totalPublishedCount'
        ));
    }

    /**
     * Display a single post detail page.
     */
    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->with(['category', 'author.trainer'])
            ->firstOrFail();

        // If draft, only admin or author can preview
        if ($post->status !== 'published') {
            if (!auth()->check() || (!auth()->user()->isAdmin() && auth()->id() !== $post->author_id)) {
                abort(404);
            }
        }

        // Related posts from same category or latest published
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($relatedPosts->count() < 3) {
            $fallback = Post::published()
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $relatedPosts->pluck('id'))
                ->with(['category', 'author'])
                ->latest('published_at')
                ->take(3 - $relatedPosts->count())
                ->get();
            $relatedPosts = $relatedPosts->concat($fallback);
        }

        return view('posts.show', compact('post', 'relatedPosts'));
    }
}
