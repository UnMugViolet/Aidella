<?php

namespace App\Orchid\Screens;

use App\Models\DogRace;
use App\Models\BlogPost;
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

class BlogPostDogRaceEditScreen extends Screen
{
    private const NULLABLE_STRING_MAX_255 = 'nullable|string|max:255';

    public $name = 'Modifier une page de chien';
    public $description = 'Modifiez la race de chien et la page associée.';

    public $dogRace;
    public $blogPost;

    public function query(DogRace $dogRace): iterable
    {
        $blogPost = BlogPost::where('dog_race_id', $dogRace->id)->first();

        $dogRaceData = $dogRace->toArray();
        $dogRaceData['thumbnail'] = optional($dogRace->pictures()->where('is_main', true)->first())->path;

        $blogPostData = $blogPost ? $blogPost->toArray() : [];

        // If you use a rich text editor, ensure 'html' is set
        $blogPostData['html'] = $blogPostData['content'] ?? '';

        // Pictures for gallery
        $blogPostData['pictures'] = $blogPost
            ? $blogPost->pictures()->where('is_main', false)->pluck('path')->toArray()
            : [];

        return [
            'dogRace' => $dogRaceData,
            'post' => $blogPostData,
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
                        ->placeholder('Ordre')
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
            'dogRace.order' => 'integer|min:1',
            'post.title' => 'required|string|max:255',
            'post.status' => 'required|in:draft,published,archived',
            'post.author_id' => 'required|exists:users,id',
            'post.html' => 'required|string',
            'post.meta_title' => self::NULLABLE_STRING_MAX_255,
            'post.meta_description' => self::NULLABLE_STRING_MAX_255,
        ]);

        if (DogRace::where('name', $data['name'])->where('id', '!=', $dogRace->id)->exists()) {
            Toast::error('Cette race de chien existe déjà.');
            return null;
        }

        $dogRace->fill($data)->save();

        if (!empty($data['thumbnail'])) {
            $this->saveThumbnail($dogRace, $data['thumbnail'], $data['name']);
        }

        $blogPostModel = BlogPost::where('dog_race_id', $dogRace->id)->first();
        if ($blogPostModel) {
            $blogPostModel->update([
                'title' => $blogPost['title'],
                'slug' => Str::slug($data['name'], '-', 'fr'),
                'content' => $blogPost['html'] ?? '',
                'meta_title' => $blogPost['meta_title'] ?? 'Page de chien - ' . $dogRace->name,
                'meta_description' => $blogPost['meta_description'] ?? 'Description page de chien pour ' . $dogRace->name,
                'status' => $blogPost['status'],
                'author_id' => $blogPost['author_id'],
            ]);
            // Remove old gallery pictures
            $blogPostModel->pictures()->where('is_main', false)->delete();
            $this->saveGalleryPictures($blogPostModel, $blogPost['pictures'] ?? [], $dogRace->name);
        }

        Toast::success('Race de chien et page associée modifiées avec succès!');
        return redirect()->route('platform.dog-races');
    }

    private function saveThumbnail(DogRace $dogRace, $thumbnail, $altText)
    {
        $thumbnailPath = $this->parsePath($thumbnail);
        $dogRace->pictures()->where('is_main', true)->delete();
        $dogRace->pictures()->create([
            'path' => $thumbnailPath,
            'is_main' => true,
            'alt_text' => $altText,
            'is_main' => true,
        ]);
    }

    private function saveGalleryPictures(BlogPost $post, array $pictures, $altText)
    {
        foreach ($pictures as $picturePath) {
            $cleanPath = $this->parsePath($picturePath);
            $post->pictures()->create([
                'path' => $cleanPath,
                'alt_text' => $altText,
                'is_main' => false,
            ]);
        }
    }

    private function parsePath($path)
    {
        $parsedUrl = parse_url($path, PHP_URL_PATH);
        return ltrim($parsedUrl, '/');
    }
}
