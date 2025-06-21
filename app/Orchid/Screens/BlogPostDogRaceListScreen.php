<?php

namespace App\Orchid\Screens;

use App\Models\BlogPost;
use App\Models\DogRace;
use App\Orchid\Layouts\DogRaceListLayout;
use Orchid\Screen\Screen;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Orchid\Support\Facades\Toast;

class BlogPostDogRaceListScreen extends Screen
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
        $dogRaceId = $request->get('id');

        $blogPost = BlogPost::where('dog_race_id', $dogRaceId)
            ->whereNull('category_id')
            ->first();

        if ($blogPost) {
            $blogPost->delete();
        }
        Toast::info(__('Le chien et sa page associée ont été supprimées avec succès !'));
    }
}
