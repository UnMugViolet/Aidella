<?php

namespace App\Orchid\Screens;

use App\Models\DogRace;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Picture;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DogRaceScreen extends Screen
{
    public $dogRace;
    public $name = 'Gestion des races de chiens';
    public $description = 'Cette page d\'ajouter une race de chiens. La création d\'une race de chien entraine la création d\'une page de chiens.';

    public function query(DogRace $dogRace): iterable
    {
        $this->dogRace = $dogRace;
        return [
            'dogRace' => $dogRace,
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Enregistrer')
                ->icon('check')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('dogRace.name')
                    ->title('Nom')
                    ->placeholder('Nom de la race')
                    ->required(),

                TextArea::make('dogRace.description')
                    ->title('Description')
                    ->rows(5)
                    ->placeholder('Description de la race'),

                Picture::make('dogRace.thumbnail')
                    ->title('Miniature')
                    ->storage('public')
                    ->path('uploads/dog-races')
                    ->acceptedFiles('image/*')
                    ->maxFiles(1)
                    ->help('Téléchargez une image miniature'),
            ]),
        ];
    }

    public function save(DogRace $dogRace, Request $request)
    {
        $data = $request->get('dogRace');

            // Validate the data
        $request->validate([
            'dogRace.name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\pN\s\-]+$/u',
            ],
            'dogRace.description' => 'nullable|string|max:255',
            'dogRace.thumbnail' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Save only if the name is not already taken
        if (DogRace::where('name', $data['name'])->where('id', '!=', $dogRace->id)->exists()) {
            Toast::error('Cette race de chien existe déjà.');
            return null;
        }
        
        $data['slug'] = Str::slug($data['name'], '-', 'fr');
        $max_order = DogRace::max('order');

        $data['order'] = $max_order ? $max_order + 1 : 1;
        $dogRace->fill($data)->save();

        if (!empty($data['thumbnail'])) {
            // Remove old thumbnails
            $parsedUrl = parse_url($data['thumbnail'], PHP_URL_PATH);
            $thumbnailPath = ltrim($parsedUrl, '/');

            // Save new thumbnail
            $dogRace->pictures()->create([
                'path' => $thumbnailPath,
                'is_main' => true,
                'alt_text' => $data['name'],
                'type' => 'thumbnail',
            ]);
        }

        Toast::success('Race de chien enregistrée avec succès!');
        return redirect()->route('platform.dog-races');
    }
}
