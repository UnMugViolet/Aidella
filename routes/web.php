<?php

use App\Models\DogRace;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SingleDogController;
use App\Models\BlogPost;

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

// Generate all the routes for the Dog Pages
Route::get('{slug}', [SingleDogController::class, 'show']);

