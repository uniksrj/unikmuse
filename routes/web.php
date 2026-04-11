<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Blogmain;
use Illuminate\Support\Facades\Route;

Route::get('/', [Blogmain::class, 'main'])->name('home');

Route::match(['get', 'post'], 'login', [AdminController::class, 'handleLogin'])
    ->name('login');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [Admin::class, 'index'])->name('admin');

    Route::get('/admin/drafts', [Admin::class, 'drafts'])->name('admin.drafts');
    Route::get('/admin/drafts/{id}/preview', [Admin::class, 'previewDraft'])->name('admin.drafts.preview');
    Route::patch('/admin/drafts/{id}/publish', [Admin::class, 'publishDraft'])->name('admin.drafts.publish');

    Route::post('/delete_session', [AdminController::class, 'deleteSessionData'])->name('delete');

    Route::get('/adduser', [Admin::class, 'addpost'])->name('addpost');
    Route::post('/saveData', [Admin::class, 'save_post'])->name('saveData');

    Route::get('/editPage/{id}', [Admin::class, 'view_editPage']);
    Route::put('/update_data/{id}', [Admin::class, 'update_data']);
    Route::delete('/delete_data/{id}', [Admin::class, 'delete_data']);
});

Route::get('/blog', [Blogmain::class, 'index'])->name('blog.index');
Route::get('/blog/{slugOrId}', [Blogmain::class, 'show'])->name('blog.show');
Route::get('/posts/{slugOrId}', [Blogmain::class, 'show'])->name('post.show');
Route::post('/posts/{id}/comment', [Blogmain::class, 'storeComment'])->name('post.comment');
Route::get('/sitemap.xml', [Blogmain::class, 'sitemap'])->name('sitemap');

Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contactUs')->name('contact');

Route::get('/category/{categorySlug}', [Blogmain::class, 'byCategory'])
    ->name('category.show')
    ->where('categorySlug', 'technology|travel|life-style|digital-trends|productivity|news-updates|stories-experiences|creativity-inspiration');

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
