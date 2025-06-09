<?php

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\ContactFormController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SingleDogController;
use App\Models\BlogPost;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

// Sitemap generation
Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create()
        ->add(Url::create('/'))
        ->add(Url::create('/a-propos'))
        ->add(Url::create('/articles'));

    foreach (BlogPost::all() as $post) {
        if (!$post->category || !$post->category->slug) {
            $sitemap->add(Url::create(route('dog.show', ['slug' => $post->slug])));
            continue;
        }
        $sitemap->add(Url::create(route('blog.show', [
            'category' => $post->category->slug,
            'slug' => $post->slug,
        ])));
    }
    return $sitemap->toResponse(request());
});


Route::get('/', [HomeController::class, 'index']);
Route::get('/a-propos', fn() => view('about'));
Route::get('/mentions-legales', fn() => view('legal_mentions'));
Route::get('/cgu', fn() => view('privacy_policy'));
Route::post('/contact', [ContactFormController::class, 'submit']);
Route::get('/articles', [BlogPostController::class, 'index']);


Route::get('/{category}/{slug}', [BlogPostController::class, 'show'])
    ->where('category', '^(?!admin|aidella-admin-panel|dashboard|orchid).*')
    ->name('blog.show');

// Single dog profile
Route::get('{slug}', [SingleDogController::class, 'show'])
    ->where('slug', '^(?!admin|aidella-admin-panel|dashboard|orchid).*')
    ->name('dog.show');
