<?php

namespace App\Orchid\Screens;


use App\Models\BlogPost;
use Orchid\Attachment\Models\Attachment;
use App\Orchid\Layouts\BlogPostCategoryLayout;
use App\Orchid\Layouts\BlogPostPicturesLayout;
use App\Orchid\Layouts\BlogPostSeoLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Str;

class BlogPostEditScreen extends Screen
{
    private const NULLABLE_STRING_MAX_255 = 'nullable|string|max:255';

    public $name = 'Modifier un article';
    public $description = 'Cette page permet de modifier un article de blog, ajouter ou supprimer du contenu, des photos ou modifier le statut';

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(BlogPost $blogPost): iterable
    {
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
            'post' => $blogPostData,
        ];
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
                ->method('update'),
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
                'Contenu' => BlogPostCategoryLayout::class,
                'SEO'     => BlogPostSeoLayout::class,
                'Images'  => BlogPostPicturesLayout::class,
            ]),
        ];
    }

    public function update(BlogPost $blogPost, Request $request)
    {
        $data = $request->get('post', []);

        $request->validate([
            'post.title' => 'required|string|max:255',
            'post.status' => 'in:draft,published,archived',
            'post.html' => 'required',
            'post.meta_title' => self::NULLABLE_STRING_MAX_255,
            'post.meta_description' => self::NULLABLE_STRING_MAX_255,
            'post.slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
            ],
        ]);

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        } else {
            $data['published_at'] = null;
        }

        $blogPost->fill($data);
        $blogPost->save();

        // Remove old gallery pictures
        foreach ($blogPost->pictures()->where('is_main', false)->get() as $picture)
            $this->deletePictureAndAttachment($picture);
        $this->saveGalleryPictures($blogPost, $data['gallery'] ?? [], 'alt text par défaut');    
        Toast::success(__('Article de blog mis à jour.'));
        return redirect()->route('platform.posts');
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
}
