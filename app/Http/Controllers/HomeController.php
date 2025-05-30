<?php

namespace App\Http\Controllers;

use App\Models\DogRace;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $dogRaces = DogRace::all();

        // Remove the 'slug' attribute from the collection
        $dogRaces->makeHidden('slug');

        return view('homepage', compact('dogRaces'));
    }
}
