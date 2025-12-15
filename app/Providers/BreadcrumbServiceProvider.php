<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class BreadcrumbServiceProvider extends ServiceProvider
{
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
                        ['label' => 'Home', 'url' => url('/')],
                        ['label' => 'Blog'],
                    ];
                    break;

                case 'categories.index':
                    $breadcrumbs = [
                        ['label' => 'Home', 'url' => url('/')],
                        ['label' => 'Categories'],
                    ];
                    break;

                case 'categories.show':
                    if ($category = request()->route('slug')) {
                        $breadcrumbs = [
                            ['label' => 'Home', 'url' => url('/')],
                            ['label' => 'Blog', 'url' => url('/blog')],
                            ['label' => 'Categories', 'url' => url('/categories')],
                            ['label' => ucwords(str_replace('-', ' ', $category))],
                        ];
                    }
                    break;

                case 'post.show':
                    if ($post = request()->route('id')) {
                        $breadcrumbs = [
                            ['label' => 'Home', 'url' => url('/')],
                            ['label' => 'Blog', 'url' => url('/blog')],
                            ['label' => 'Post'],
                        ];
                    }
                    break;
            }

            $view->with('breadcrumbs', $breadcrumbs);
        });
    }
}
