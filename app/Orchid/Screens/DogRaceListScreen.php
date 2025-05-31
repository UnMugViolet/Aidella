<?php

namespace App\Orchid\Screens;

use App\Models\DogRace;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;

class DogRaceListScreen extends Screen
{
    public $name = 'Liste des races de chiens';

    public function query(): array
    {
        return [
            'dogRaces' => DogRace::paginate(),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::table('dogRaces', [
                TD::make('name', 'Nom')->sort()->filter(),
                TD::make('description', 'Description'),
				TD::make('slug', 'Slug'),

				TD::make('created_at', 'Créé le')
					->sort()
					->render(function (DogRace $dogRace) {
						return $dogRace->created_at->format('d/m/Y H:i');
					}),
            ]),
        ];
    }

    public function destroy($id)
    {
        $dogRace = DogRace::findOrFail($id);
        $dogRace->delete();

        Toast::success('Race de chien supprimée.');
        return redirect()->route('platform.dog-races');
    }
}
