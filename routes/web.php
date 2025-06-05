<?php

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\ContactFormController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SingleDogController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/a-propos', fn() => view('about'));
Route::get('/mentions-legales', fn() => view('legal_mentions'));
Route::get('/politique-de-confidentialite', fn() => view('privacy_policy'));
Route::post('/contact', [ContactFormController::class, 'submit']);
Route::get('/articles', [BlogPostController::class, 'index']);


Route::get('/{category}/{slug}', [BlogPostController::class, 'show'])
    ->where('category', '^(?!admin|aidella-admin-panel|dashboard|orchid).*');

// Single dog profile
Route::get('{slug}', [SingleDogController::class, 'show'])
    ->where('slug', '^(?!admin|aidella-admin-panel|dashboard|orchid).*');

