<?php

namespace App\Orchid\Screens;

use App\Models\BlogPost;
use App\Models\DogRace;
use App\Orchid\Layouts\DogRaceListLayout;
use Orchid\Screen\Screen;

use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class DogRaceListScreen extends Screen
{
    public $name = 'Liste des races de chiens';
    public $description = 'Retrouvez ici la liste des pages de chiens vous pourrez les modifier ou les supprimer depuis l\'option "Action"';

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'dogRaces' => DogRace::filters()
                ->defaultSort('order', 'asc')
                ->paginate(),
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            DogRaceListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        $dogRace = DogRace::findOrFail($request->get('id'));
        $blogPost = BlogPost::where('dog_race_id', $dogRace->id)
            ->whereNull('category_id')
            ->first();

        if ($blogPost) {
            $blogPost->delete();
        }
        $dogRace->delete();

        Toast::info(__('Race de chien et page associée supprimée'));
    }
}
