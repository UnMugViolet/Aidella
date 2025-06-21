<?php

namespace App\Http\Controllers;

use App\Models\DogRace;
use Illuminate\Http\Request;
use Illuminate\Queue\NullQueue;
use SebastianBergmann\Type\NullType;

class HomeController extends Controller
{
    public function index()
    {
        $dogRaces = null;

        return view('homepage', compact('dogRaces'));
    }
}
