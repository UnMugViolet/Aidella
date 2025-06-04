<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\DogRace;
use Illuminate\Http\Request;

class SingleDogController extends Controller
{

    public function show($slug)
    {
        $blogPost = BlogPost::with(['dogRace', 'pictures'])
            ->where('slug', $slug)
            ->whereNotNull('dog_race_id')
            ->firstOrFail();

        $dogRaces = DogRace::with('blogPost')->get();

        return view('single_dog', [
            'blogPost' => $blogPost,
            'dogRaces' => $dogRaces,
        ]);
    }
}
