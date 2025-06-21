<?php

namespace App\Orchid\Screens;

use App\Models\DogRace;
use App\Models\BlogPost;
use App\Orchid\Layouts\BlogPostDogRaceLayout;
use App\Orchid\Layouts\BlogPostPicturesLayout;
use Orchid\Attachment\Models\Attachment;
use App\Orchid\Layouts\BlogPostSeoLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Orchid\Screen\Fields\Attach;
use Orchid\Support\Facades\Toast;
use PhpParser\Node\Expr\FuncCall;

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

        $blogPostData = $blogPost ? $blogPost->toArray() : [];
        $blogPostData['html'] = $blogPostData['content'] ?? '';
        $dogRaceData['thumbnail'] = $dogRace->attachments()->where('group', 'thumbnail')->get();
        $blogPostData['gallery'] = $blogPost->attachments()->where('group', 'gallery')->get();

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

                    Attach::make('dogRace.thumbnail')
                        ->title('Miniature')
                        ->storage('public')
                        ->path('uploads/dog-races')
                        ->acceptedFiles('image/*')
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

        $request->validate([
            'dogRace.name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\pN\s\-]+$/u',
            ],
            'dogRace.description' => self::NULLABLE_STRING_MAX_255,
            'dogRace.thumbnail' => 'nullable',
            'dogRace.order' => 'integer|min:1',
            'post.title' => 'required|string|max:255',
            'post.status' => 'required|in:draft,published,archived',
            'post.author_id' => 'required|exists:users,id',
            'post.html' => 'required|string',
            'post.slug' => 'string|max:255|nullable',
            'post.meta_title' => self::NULLABLE_STRING_MAX_255,
            'post.meta_description' => self::NULLABLE_STRING_MAX_255,
        ]);

        if (DogRace::where('name', $data['name'])->where('id', '!=', $dogRace->id)->exists()) {
            Toast::error('Cette race de chien existe déjà.');
            return null;
        }

        $this->saveThumbnail($dogRace, $data['thumbnail']);

        $dogRace->fill($data)->save();

        $blogPostModel = BlogPost::where('dog_race_id', $dogRace->id)->first();
        if ($blogPostModel) {
            $blogPostModel->update([
                'title' => $blogPost['title'],
                'slug' => $blogPost['slug'] ?? Str::slug($data['name'], '-', 'fr'),
                'content' => $blogPost['html'] ?? '',
                'meta_title' => $blogPost['meta_title'] ?? 'Page de chien - ' . $dogRace->name,
                'meta_description' => $blogPost['meta_description'] ?? 'Description page de chien pour ' . $dogRace->name,
                'status' => $blogPost['status'],
                'author_id' => $blogPost['author_id'],
            ]);

            $newGalleryIds = $request->input('gallery', []);
            $blogPostModel->attachments()->sync($newGalleryIds);
                $removed = $blogPostModel->attachments()->whereNotIn('id', $newGalleryIds)->get();
            foreach ($removed as $attachment) {
                $attachment->delete();
            }
            $this->saveGalleryPictures($blogPostModel, $blogPost['gallery'] ?? [], 'image carroussel pour le ' . $dogRace->name);
        }

        Toast::success('Race de chien et page associée modifiées avec succès!');
        return redirect()->route('platform.dog-races');
    }

    private function saveThumbnail(DogRace $dogRace, $thumbnailId)
    {
        $dogRace->attachments()->where('attachments.group', 'thumbnail')->detach();

        $attachment = Attachment::find($thumbnailId);
        if ($attachment) {
            $attachment->group = 'thumbnail';
            $attachment->alt = $dogRace->name . ' - Miniature';
            $attachment->save();

            $dogRace->attachments()->attach($attachment->id);
        }
    }

    private function saveGalleryPictures(BlogPost $post, $pictures, $altText)
    {
        if (empty($pictures)) {
            return;
        }

        $currentPictures = $post->attachments()->where('group', 'gallery')->get();
        $currentPictureIds = $currentPictures->pluck('id')->toArray();
        $newPictureIds = [];

        foreach ($pictures as $picture) {
            if (is_string($picture)) {
                // If it's a string, it means it's an existing attachment ID
                $attachment = Attachment::find($picture);
                if ($attachment) {
                    $newPictureIds[] = $attachment->id;
                    $attachment->alt = $altText;
                    $attachment->group = 'gallery';
                    $attachment->save();
                }
            } elseif (is_array($picture) && isset($picture['id'])) {
                // If it's an array with an ID, find the attachment
                $attachment = Attachment::find($picture['id']);
                if ($attachment) {
                    $newPictureIds[] = $attachment->id;
                    $attachment->alt = $altText;
                    $attachment->group = 'gallery';
                    $attachment->save();
                }
            }
        }
        // Attach new pictures that are not already attached
        $newPictures = array_diff($newPictureIds, $currentPictureIds);

        foreach ($newPictures as $pictureId) {
            $post->attachments()->attach($pictureId);
        }
    }
}
