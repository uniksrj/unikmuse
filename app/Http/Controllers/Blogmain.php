<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\newpost_details;
use App\Models\PostView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Blogmain extends Controller
{

    public function __construct() {}

    public function index()
    {

        $featuredPosts = newpost_details::withCount('views')
            ->published()
            ->featured()
            ->recent()
            ->limit(4)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt,
                    'content' => $post->description,
                    'category' => $post->category,
                    'thumbnail' => json_decode($post->file_path, true),
                    'is_featured' => (bool)$post->is_featured,
                    'views' => $post->views_count,
                    'estimated_reading_time' => $post->estimated_reading_time,
                    'tags' => $post->tags,
                    'published_at' => $post->created_date ? $post->created_date->toDateTimeString() : null,
                    'created_at' => $post->created_at ? $post->created_at->toDateTimeString() : null,
                    'updated_at' => $post->updated_at ? $post->updated_at->toDateTimeString() : null
                ];
            });

        $posts = newpost_details::withCount('views')
            ->published()
            ->recent()
            ->paginate(8)
            ->through(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt,
                    'content' => $post->description,
                    'category' => $post->category,
                    'thumbnail' => json_decode($post->file_path, true),
                    'is_featured' => (bool)$post->is_featured,
                    'views' => $post->views_count,
                    'estimated_reading_time' => $post->estimated_reading_time,
                    'tags' => $post->tags,
                    'published_at' => $post->created_date ? $post->created_date->toDateTimeString() : null,
                    'created_at' => $post->created_at ? $post->created_at->toDateTimeString() : null,
                    'updated_at' => $post->updated_at ? $post->updated_at->toDateTimeString() : null
                ];
            });

        $popularPosts = newpost_details::withCount('views')
            ->published()
            ->popular()
            ->limit(5)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'category' => $post->category,
                    'thumbnail' => json_decode($post->file_path, true),
                    'views' => $post->views_count,
                    'estimated_reading_time' => $post->estimated_reading_time,
                    'published_at' => $post->created_date ? $post->created_date->toDateTimeString() : null
                ];
            });

        $categories = newpost_details::published()
            ->select('category', DB::raw('COUNT(*) as count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category')
            ->toArray();

        $defaultCategories = [
            'Technology' => 0,
            'Travel' => 0,
            'Lifestyle' => 0,
            'Digital Trends' => 0,
            'Productivity' => 0,
            'Tutorials' => 0,
            'News & Updates' => 0,
            'Stories & Experiences' => 0,
            'Creativity & Inspiration' => 0
        ];

        $categoriesList = [];

        foreach ($defaultCategories as $label => $defaultCount) {
            $lowerKey = strtolower($label);
            $lowerKey = str_replace('&', '', $lowerKey);
            $lowerKey = trim($lowerKey);
            $lowerKey = str_replace(' ', '-', $lowerKey);
            $categoriesList[$label] = $categories[$lowerKey] ?? 0;
        }

        return view('pages.blogPage', [
            'featuredPosts' => $featuredPosts,
            'posts' => $posts,
            'popularPosts' => $popularPosts,
            'categories' => $categories,
            'categoriesList' => $categoriesList,
            'category' => request()->get('category')
        ]);
    }

    public function main(Request $request)
    {
        $encoded = $request->input('q');
        $query = $encoded ? base64_decode($encoded) : null;
        $data = $this->getData($query);
        return view('main.home', ['data' => $data]);
    }

    public function getData($query = null)
    {
        $posts = DB::table('newpost_details')
            ->select('*');

        if (!empty($query)) {
            $posts->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%");
            });
        }

        return $posts->get();
    }

    public function show($id)
    {
        try {
            $post = newpost_details::published()->findOrFail($id);

            try {
                $this->trackView($post);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("View tracking failed: " . $e->getMessage());
            }

            $images = $post->images;

            $relatedPosts = newpost_details::published()
                ->where('id', '!=', $post->id)
                ->where('category', $post->category)
                ->recent()
                ->limit(3)
                ->get();

            $popularPosts = newpost_details::published()
                ->where('id', '!=', $post->id)
                ->recent()
                ->limit(5)
                ->get();

            $comments = Comment::where('post_id', $post->id)
                ->approved()
                ->orderBy('created_at', 'desc')
                ->get();

            $viewStats = [
                'today' => PostView::todayViews($post->id) ?? 0,
                'total' => PostView::totalViews($post->id) ?? 0,
                'unique' => PostView::uniqueViews($post->id) ?? 0,
            ];

            return view('main.readmore', compact(
                'post',
                'images',
                'relatedPosts',
                'popularPosts',
                'comments',
                'viewStats'
            ));
        } catch (\Exception $e) {
            abort(404, 'Post not found');
        }
    }

    /**
     * Track post view
     */
    private function trackView($post)
    {
        //         ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $ipAddress = request()->ip();
        $sessionId = session()->getId();
        // Check if already viewed today
        if (!PostView::hasViewedToday($post->id, $ipAddress)) {
            PostView::create([
                'post_id' => $post->id,
                'ip_address' => $ipAddress,
                'session_id' => $sessionId,
                'user_agent' => request()->header('User-Agent')
            ]);

            // If you add views_count column, uncomment:
            // $post->increment('views_count');
        }
    }

    /**
     * Submit a comment
     */
    public function storeComment(Request $request, $postId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string|min:5|max:1000',
        ]);

        $comment = Comment::create([
            'post_id' => $postId,
            'name' => $request->name,
            'email' => $request->email,
            'content' => $request->content,
            'user_ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent')
        ]);

        // If you add comments_count column, uncomment:
        // Post::find($postId)->increment('comments_count');

        return back()->with('success', 'Comment submitted successfully! It will appear after approval.');
    }

    /**
     * Show posts by category
     */
    public function byCategory($category)
    {
        
        $defaultCategories = [
            'technology' => 'Technology',
            'travel' => 'Travel',
            'life-style' => 'Lifestyle',
            'digital-trends' => 'Digital Trends',
            'productivity' => 'Productivity',
            'tutorials' => 'Tutorials',
            'news-updates' => 'News & Updates',
            'stories-experiences' => 'Stories & Experiences',
            'creativity-inspiration' => 'Creativity & Inspiration'
        ];

        if (!isset($defaultCategories[$category])) {
            abort(404);
        }
        
        $displayName = $defaultCategories[$category];
        // $category = $defaultCategories[$category];
        $posts = newpost_details::published()
            ->where('category', $category)
            ->recent()
            ->paginate(10);

        return view('categories.showPage', [
        'posts' => $posts,
        'categoryName' => $displayName, 
        'categorySlug' => $category 
    ]);
    }
}
