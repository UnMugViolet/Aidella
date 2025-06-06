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
        if ($dogRaceData['thumbnail'] && $dogRaceData['thumbnail'][0] !== '/') {
            $dogRaceData['thumbnail'] = '/' . $dogRaceData['thumbnail'];
        }

        $blogPostData = $blogPost ? $blogPost->toArray() : [];
        $blogPostData['html'] = $blogPostData['content'] ?? '';

        // Get attachment IDs for gallery pictures
        $galleryAttachmentIds = [];
        if ($blogPost) {
            foreach ($blogPost->pictures()->where('is_main', false)->get() as $picture) {
                $filename = pathinfo($picture->path, PATHINFO_FILENAME);
                $extension = pathinfo($picture->path, PATHINFO_EXTENSION);
                $attachment = Attachment::where('name', $filename)
                    ->where('extension', $extension)
                    ->first();
                if ($attachment) {
                    $galleryAttachmentIds[] = $attachment->id;
                }
            }
        }
        $blogPostData['gallery'] = $galleryAttachmentIds;

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
            'post.slug' => 'string|max:255|nullable',
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
        } else {
            // If thumbnail is removed, delete the old one
            $oldThumbnail = $dogRace->pictures()->where('is_main', true)->first();
            if ($oldThumbnail) {
                $this->deletePictureAndAttachment($oldThumbnail);
            }
        }

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
            // Remove old gallery pictures
            foreach ($blogPostModel->pictures()->where('is_main', false)->get() as $picture) {
                $this->deletePictureAndAttachment($picture);
            }
            $this->saveGalleryPictures($blogPostModel, $blogPost['gallery'] ?? [], $dogRace->name);
        }

        Toast::success('Race de chien et page associée modifiées avec succès!');
        return redirect()->route('platform.dog-races');
    }

    private function saveThumbnail(DogRace $dogRace, $thumbnail, $altText)
    {
        $oldThumbnail = $dogRace->pictures()->where('is_main', true)->first();
        if ($oldThumbnail) {
            $this->deletePictureAndAttachment($oldThumbnail);
        }
        $thumbnailPath = $this->parsePath($thumbnail);
        $dogRace->pictures()->create([
            'path' => $thumbnailPath,
            'is_main' => true,
            'alt_text' => $altText,
        ]);
    }

    private function saveGalleryPictures(BlogPost $post, $pictures, $altText)
    {
        $currentPictures = $post->pictures()->where('is_main', false)->get();
        $currentAttachmentIds = [];
        foreach ($currentPictures as $picture) {
            $filename = pathinfo($picture->path, PATHINFO_FILENAME);
            $extension = pathinfo($picture->path, PATHINFO_EXTENSION);
            $attachment = Attachment::where('name', $filename)
                ->where('extension', $extension)
                ->first();
            if ($attachment) {
                $currentAttachmentIds[$attachment->id] = $picture;
            }
        }

        foreach ($currentAttachmentIds as $attachmentId => $picture) {
            if (!in_array($attachmentId, $pictures)) {
                $this->deletePictureAndAttachment($picture);
            }
        }
        // Add new pictures that are not already present
        foreach ($pictures as $attachmentId) {
            if (!isset($currentAttachmentIds[$attachmentId])) {
                $attachment = Attachment::find($attachmentId);
                if ($attachment) {
                    $storagePath = 'storage/' . ltrim($attachment->path, '/') . '/' . $attachment->name . '.' . $attachment->extension;
                    $post->pictures()->create([
                        'path' => $storagePath,
                        'alt_text' => $altText,
                        'is_main' => false,
                    ]);
                }
            }
        }
    }

    private function deletePictureAndAttachment($picture)
    {
        $filename = pathinfo($picture->path, PATHINFO_FILENAME);
        $extension = pathinfo($picture->path, PATHINFO_EXTENSION);
        $attachment = Attachment::where('name', $filename)
            ->where('extension', $extension)
            ->first();
        if ($attachment) {
            $attachment->delete();
        } else {
            $cleanPath = ltrim($picture->path, '/');
            if (Storage::disk('public')->exists($cleanPath)) {
                Storage::disk('public')->delete($cleanPath);
            }
        }
        if (!$attachment) {
            Log::warning('Attachment not found for: ' . $picture->path);
        }
        $picture->delete();
    }

    private function parsePath($path)
    {
        $parsedUrl = parse_url($path, PHP_URL_PATH);
        return ltrim($parsedUrl, '/');
    }
}
