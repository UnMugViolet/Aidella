<?php

namespace App\Http\Controllers;

use App\Models\DogRace;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $dogRaces = DogRace::with('attachments')->get();

        return view('homepage', compact('dogRaces'));
    }
}
