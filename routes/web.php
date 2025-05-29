<?php

use App\Models\DogRace;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
});

Route::get('/about', function () {
    return view('about');
});


// Routes for all the dogs races
Route::get('/race/{slug}', function ($slug) {
    $dogRace = DogRace::where('slug', $slug)->firstOrFail();
    return view('dog_race', ['dogRace' => $dogRace]);
});
