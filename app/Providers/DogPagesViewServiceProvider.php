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
        View::share('dogPages', DogRace::whereHas('blogPost', function ($query) {
                $query->where('status', 'published');
            })
            ->with(['blogPost' => function ($query) {
                $query->where('status', 'published'); // Ensure only published blog posts are included
            }])
            ->with('attachments')
            ->orderBy('order')
            ->get());
    }
}
