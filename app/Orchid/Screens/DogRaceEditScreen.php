<?php

namespace App\Orchid\Screens;

use App\Models\DogRace;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Picture;

class DogRaceEditScreen extends Screen
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
        return 'Mettre à jour une fiche de chien';
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
                    ->help('Téléchargez une image miniature')
                    ->value('/' . $this->dogRace->main_image ?? null), // Add this line for preview
            ]),
        ];
    }

    public function save(DogRace $dogRace, Request $request)
    {
        $data = $request->get('dogRace');

        // Check for duplicate name
        if (DogRace::where('name', $data['name'])->where('id', '!=', $dogRace->id)->exists()) {
            Toast::error('Cette race de chien existe déjà.');
            return null;
        }

        $data['slug'] = $this->generateSlug($data['name']);
        $dogRace->fill($data)->save();

        if (!empty($data['thumbnail'])) {
            // Remove old thumbnails from storage and database
            $oldThumbnails = $dogRace->pictures()->where('is_main', true)->get();
            foreach ($oldThumbnails as $old) {
                if (Storage::disk('public')->exists($old->path)) {
                    Storage::disk('public')->delete($old->path);
                }
                $old->delete();
            }

            // Save new thumbnail
            $parsedUrl = parse_url($data['thumbnail'], PHP_URL_PATH);
            $thumbnailPath = ltrim($parsedUrl, '/');

            $dogRace->pictures()->create([
                'path' => $thumbnailPath,
                'is_main' => true,
                'alt_text' => $data['name'],
                'type' => 'thumbnail',
            ]);
        }

        Toast::success('Race de chien mise à jour avec succès!');
        return redirect()->route('platform.dog-races');
    }

    private function generateSlug(string $name): string
    {
        $slug = strtolower($name);
        $slug = str_replace('é', 'e', $slug);
        $slug = preg_replace('/\s+/', '-', $slug);
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
