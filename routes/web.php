<?php

use App\Http\Controllers\BlogPostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SingleDogController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/a-propos', function () {
    return view('about');
});

Route::get('/mentions-legales', function () {
    return view('legal_mentions');
});

Route::get('/politique-de-confidentialite', function () {
    return view('privacy_policy');
});

// Blog Articles
Route::get('/articles', [BlogPostController::class, 'index']);
Route::get('/{category}/{slug}', [BlogPostController::class, 'show']);

// Generate all the routes for the Dog Pages
Route::get('{slug}', [SingleDogController::class, 'show']);
