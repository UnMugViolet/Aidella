<?php

namespace App\Orchid\Screens;

use App\Models\BlogPost;
use App\Models\DogRace;
use App\Orchid\Layouts\BlogPostDogRaceLayout;
use App\Orchid\Layouts\BlogPostPicturesLayout;
use App\Orchid\Layouts\BlogPostSeoLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Support\Facades\Toast;

class BlogPostDogRaceScreen extends Screen
{
    private const NULLABLE_STRING_MAX_255 = 'nullable|string|max:255';

    public $name = 'Ajouter une page de chien';
    public $description = 'Cette page permet d\'ajouter une race de chiens. La création d\'une race de chien entraine la création d\'une page liée.';

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Enregistrer')
                ->icon('check')
                ->method('save')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::tabs([
                'Informations' => Layout::rows([
                    Input::make('dogRace.name')
                        ->title('Nom')
                        ->placeholder('Nom de la race')
                        ->required(),

                    TextArea::make('dogRace.description')
                        ->title('Description')
                        ->rows(5)
                        ->placeholder('Description de la race'),

                    Input::make('dogRace.order')
                        ->type('number')
                        ->title('Ordre d\'affichage')
                        ->placeholder(DogRace::max('order') + 1)
                        ->default(DogRace::max('order') + 1)
                        ->help('Plus le nombre est petit, plus la race apparaît en haut dans l\'affichage.')
                        ->min(1),

                    Picture::make('dogRace.thumbnail')
                        ->title('Miniature')
                        ->storage('public')
                        ->path('uploads/dog-races')
                        ->acceptedFiles('image/*')
                        ->maxFiles(1)
                        ->help('Téléchargez une image miniature'),
                ]),
                'Contenu' => BlogPostDogRaceLayout::class,
                'SEO'     => BlogPostSeoLayout::class,
                'Images'  => BlogPostPicturesLayout::class,
            ]),
        ];
    }


    public function save(DogRace $dogRace, Request $request)
    {
        $data = $request->get('dogRace');
        $blogPost = $request->get('post', []);

        $thumbnailRule = is_string($data['thumbnail'] ?? null) ? 'nullable|string' : 'nullable|image';

        $request->validate([
            'dogRace.name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\pN\s\-]+$/u',
            ],
            'dogRace.description' => self::NULLABLE_STRING_MAX_255,
            'dogRace.thumbnail' => $thumbnailRule,
            'post.title' => self::NULLABLE_STRING_MAX_255,
            'post.status' => 'required|in:draft,published,archived',
            'post.meta_title' => self::NULLABLE_STRING_MAX_255,
            'post.meta_description' => self::NULLABLE_STRING_MAX_255,
        ]);

        if (DogRace::where('name', $data['name'])->where('id', '!=', $dogRace->id)->exists()) {
            Toast::error('Cette race de chien existe déjà.');
            return null;
        }

        $data['order'] = $data['order'] ?? DogRace::max('order') + 1;
        $dogRace->fill($data)->save();

        if (!empty($data['thumbnail'])) {
            $this->saveThumbnail($dogRace, $data['thumbnail'], $data['name']);
        }

        $createdPost = BlogPost::create([
            'title' => $blogPost['title'],
            'slug' => Str::slug($data['name'], '-', 'fr'),
            'content' => $blogPost['html'] ?? '',
            'meta_title' => $blogPost['meta_title'] ?? 'Page de chien - ' . $dogRace->name,
            'meta_description' => $blogPost['meta_description'] ?? 'Description page de chien pour ' . $dogRace->name,
            'status' => $blogPost['status'],
            'dog_race_id' => $dogRace->id,
            'author_id' => $blogPost['author_id'],
        ]);

        $this->saveGalleryPictures($createdPost, $blogPost['pictures'] ?? [], $dogRace->name);

        Toast::success('Race de chien enregistrée avec succès!');
        return redirect()->route('platform.dog-races');
    }

    /**
     * Save the main thumbnail for the DogRace.
     */
    private function saveThumbnail(DogRace $dogRace, $thumbnail, $altText)
    {
        $thumbnailPath = $this->parsePath($thumbnail);

        // Remove old thumbnails
        $dogRace->pictures()->where('is_main', true)->delete();

        $dogRace->pictures()->create([
            'path' => $thumbnailPath,
            'is_main' => true,
            'alt_text' => $altText,
            'type' => 'thumbnail',
        ]);
    }

    /**
     * Save gallery pictures for the BlogPost.
     */
    private function saveGalleryPictures(BlogPost $post, array $pictures, $altText)
    {
        foreach ($pictures as $picturePath) {
            $cleanPath = $this->parsePath($picturePath);
            $post->pictures()->create([
                'path' => $cleanPath,
                'alt_text' => $altText,
                'type' => 'gallery',
            ]);
        }
    }

    /**
     * Parse the file path from a URL or string.
     */
    private function parsePath($path)
    {
        $parsedUrl = parse_url($path, PHP_URL_PATH);
        return ltrim($parsedUrl, '/');
    }
}
