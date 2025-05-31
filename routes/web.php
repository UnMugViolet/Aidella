<?php

use App\Models\DogRace;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


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

// Routes for all the dogs races
Route::get('/race/{slug}', function ($slug) {
    $dogRace = DogRace::where('slug', $slug)->firstOrFail();
    return view('single_dog', [
        'dogRace' => $dogRace,
        'dogRaces' => DogRace::all(),
        'dogRaceJson' => $dogRace->toJson()
    ]);
});
