<?php

namespace App\Orchid\Screens;

use App\Models\DogRace;
use App\Orchid\Layouts\DogRaceListLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;

class DogRaceListScreen extends Screen
{
    public $name = 'Liste des races de chiens';

    public function query(): array
    {
        return [
            'dogRaces' => DogRace::filters()->defaultSort('order', 'asc')->paginate(),
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
