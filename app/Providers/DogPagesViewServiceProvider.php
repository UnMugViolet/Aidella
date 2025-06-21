<?php

namespace App\Providers;

use App\Models\Pictures;
use App\Models\DogRace;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class DogPagesViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share the dog pages with all views (initialData)
        View::share('dogPages', DogRace::whereHas('blogPost', function ($query) {
                $query->where('status', 'published');
            })
            ->with(['blogPost' => function ($query) {
                $query->where('status', 'published')
                    ->select('id', 'title', 'slug', 'dog_race_id');
            }])
            ->with(['attachments'])
            ->select('id', 'name', 'order')
            ->orderBy('order')
            ->get());
    }
}
