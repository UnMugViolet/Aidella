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

class DogRaceScreen extends Screen
{
    public $dogRace;

    public function query(DogRace $dogRace): iterable
    {
        $this->dogRace = $dogRace;
        return [
            'dogRace' => $dogRace,
        ];
    }

    public function name(): ?string
    {
        return 'Gestion des races de chiens';
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

        // Save only if the name is not already taken
        if (DogRace::where('name', $data['name'])->where('id', '!=', $dogRace->id)->exists()) {
            Toast::error('Cette race de chien existe déjà.');
            return null;
        }
        
        $data['slug'] = $this->generateSlug($data['name']);
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

    private function generateSlug(string $name): string
    {
        $slug = strtolower($name);
        $slug = str_replace('é', 'e', $slug);
        $slug = preg_replace('/\s+/', '-', $slug); // Replace whitespace with -
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug); // Remove special chars except -
        $slug = trim($slug, '-'); // Trim leading and trailing -
        return $slug;
    }
}
