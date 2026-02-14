<?php

namespace App\Providers;

use App\Models\newpost_details;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class BreadcrumbServiceProvider extends ServiceProvider
{

    private $categoryMap = [
        'technology' => ['label' => 'Technology', 'icon' => 'fa-microchip'],
        'travel' => ['label' => 'Travel', 'icon' => 'fa-plane'],
        'life-style' => ['label' => 'Lifestyle', 'icon' => 'fa-leaf'],
        'digital-trends' => ['label' => 'Digital Trends', 'icon' => 'fa-chart-line'],
        'productivity' => ['label' => 'Productivity', 'icon' => 'fa-bolt'],
        'tutorials' => ['label' => 'Tutorials', 'icon' => 'fa-book-open'],
        'news-updates' => ['label' => 'News & Updates', 'icon' => 'fa-newspaper'],
        'stories-experiences' => ['label' => 'Stories & Experiences', 'icon' => 'fa-feather'],
        'creativity-inspiration' => ['label' => 'Creativity & Inspiration', 'icon' => 'fa-lightbulb'],
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $route = Route::currentRouteName();
            $breadcrumbs = [];

            switch ($route) {
                case 'blog.index':
                    $breadcrumbs = [
                        ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                        ['label' => 'Blog', 'icon' => 'fa-blog'],
                    ];
                    break;

                case 'categories.index':
                    $breadcrumbs = [
                        ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                        ['label' => 'Blog', 'url' => url('/blog'), 'icon' => 'fa-blog'],
                        ['label' => 'Categories','icon' => 'fa-folder-open'],
                    ];
                    break;

                case 'category.show':
                    
                    if ($category = request()->route('categorySlug')) {                        
                        $breadcrumbs = [
                            ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                            ['label' => 'Blog', 'url' => url('/blog'), 'icon' => 'fa-blog'],
                            ['label' => 'Categories', 'url' => url('/categories'), 'icon' => 'fa-folder-open'],
                            ['label' => $this->categoryMap[$category]['label'],'icon' => $this->categoryMap[$category]['icon']]                       
                        ];
                    }                 
                    break;

                case 'post.show':
                    if ($post_id = request()->route('id')) {
                        $post = newpost_details::where('id', $post_id)->first();
                        $breadcrumbs = [
                            ['label' => 'Home', 'url' => url('/'), 'icon' => 'fa-home'],
                            ['label' => 'Blog', 'url' => url('/blog'), 'icon' => 'fa-blog'],
                            ['label' => 'Categories', 'url' => url('/categories'), 'icon' => 'fa-folder-open'],
                            ['label' =>  $this->categoryMap[$post->category]['label'], 'url' => url('/category'.'/'. $post->category), 'icon' => $this->categoryMap[$post->category]['icon']] ,
                            ['label' => $post->title, 'icon' => 'fa-file-alt'],
                        ];
                    }
                    break;
            }

            $view->with('breadcrumbs', $breadcrumbs);
        });
    }
}
