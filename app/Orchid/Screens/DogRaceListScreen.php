<?php

namespace App\Orchid\Screens;

use App\Models\DogRace;
use App\Orchid\Layouts\DogRaceListLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;

use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class DogRaceListScreen extends Screen
{
    public $name = 'Liste des races de chiens';
    public $description = 'Retrouvez ici la liste des pages de chiens vous pourrez les modifier ou les supprimer depuis l\'option "Action"';

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

    public function remove(Request $request): void
    {
        DogRace::findOrFail($request->get('id'))->delete();

        Toast::info(__('Race de chien supprimée'));
    }
}
