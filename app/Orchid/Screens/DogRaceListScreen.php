<?php

namespace App\Orchid\Screens;

use App\Models\DogRace;
use App\Orchid\Layouts\DogRaceListLayout;
use App\Orchid\Filters\DogRaceFiltersLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;

class DogRaceListScreen extends Screen
{
    public $name = 'Liste des races de chiens';

    public function query(): array
    {
        $sort = request('sort', 'order');
        $direction = request('direction', 'asc');

        // Handle Orchid's '-' prefix for descending order
        if (str_starts_with($sort, '-')) {
            $sort = ltrim($sort, '-');
            $direction = 'desc';
        }

        return [
            'dogRaces' => DogRace::orderBy($sort, $direction)->paginate(),
        ];
    }

    /**
     * @return TD[]
     */
    public function layout(): array
    {
        return [
            DogRaceListLayout::class,
        ];
    }
}
