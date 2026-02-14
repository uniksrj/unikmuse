<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blogmain;
use Illuminate\Routing\Router;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [Blogmain::class, 'main'])->name('home');
Route::match(['get', 'post'], 'login', [AdminController::class, 'handleLogin'])
    ->name('login');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

Route::get('/admin', [Admin::class, 'index'])->name('admin')->middleware('auth');
Route::middleware('auth')->group(function () {
    Route::get('/admin', [Admin::class, 'index'])->name('admin');
});

Route::post('/delete_session', [AdminController::class, 'deleteSessionData'])->name('delete');

//add Post
Route::get('/adduser', [Admin::class, 'addpost'])->name('addpost');
Route::post('/saveData', [Admin::class, 'save_post'])->name('saveData');

//Edit Page

Route::get('/editPage/{id}', [Admin::class, 'view_editPage']);
Route::put('/update_data/{id}', [Admin::class, 'update_data']);

Route::delete('/delete_data/{id}', [Admin::class, 'delete_data']);
// Route::get('/posts/{id}', [Admin::class, 'show'])->name('post.show');

Route::get('/blog', [Blogmain::class, 'index'])->name('blog.index');
Route::get('/posts/{id}', [Blogmain::class, 'show'])->name('post.show');
Route::post('/posts/{id}/comment', [Blogmain::class, 'storeComment'])->name('post.comment');
// Route::get('/category/{category}', [Blogmain::class, 'byCategory'])->name('blog.category');

Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contactUs')->name('contact');


// category route

Route::get('/category/{categorySlug}', [Blogmain::class, 'byCategory'])
    ->name('category.show')
    ->where('categorySlug', 'technology|travel|news|life-style|digital-trends|productivity|news-updates|stories-experiences|creativity-inspiration');

// Dynamic category route (if you prefer one route for all)
// Route::get('/category/{slug}', function ($slug) {
//     $category = ucwords(str_replace('-', ' ', $slug));
//     return view('categories.show', [
//         'posts' => App\Models\Post::where('category_slug', $slug)->paginate(12),
//         'category' => $category
//     ]);
// })->name('category');

Route::get('/categories', [Blogmain::class, 'categories'])->name('categories.index');

Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('privacy.policy');

Route::get('/terms-conditions', function () {
    return view('pages.terms-conditions');
})->name('terms.conditions');

Route::get('/disclaimer', function () {
    return view('pages.disclaimer');
})->name('disclaimer');