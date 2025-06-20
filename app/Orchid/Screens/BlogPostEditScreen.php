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
        $blogPostData['gallery'] = $blogPost->attachments()->get();

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
        $blogPost->content = $data['html'] ?? '';
        $blogPost->save();
        $blogPost->attachments()->sync(
            $request->get('post.gallery', []),
            ['group' => 'gallery']
        );

        $this->saveGalleryPictures($blogPost, $data['gallery'] ?? [], $blogPost->title ?? 'photo de chien illustration article');
        Toast::success(__('Article de blog mis à jour.'));
        return redirect()->route('platform.posts');
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

        // Delete pictures that are no longer in the new list
        $picturesToDetach = array_diff($currentPictureIds, $newPictureIds);
        foreach ($picturesToDetach as $pictureId) {
            $this->deleteAttachment($currentPictures->find($pictureId));
        }
    }

    private function deleteAttachment($picture)
    {
        $filename = pathinfo($picture->path, PATHINFO_FILENAME);
        $extension = pathinfo($picture->path, PATHINFO_EXTENSION);

        // Try to find the Attachment by name and extension
        $attachment = Attachment::where('name', $filename)
            ->where('extension', $extension)
            ->first();

        if ($attachment) {
            $attachment->delete();
        } else {
            // Fallback: delete the file directly
            $cleanPath = str_replace('storage/', '', $picture->path);
            if (Storage::disk('public')->exists($cleanPath)) {
                Storage::disk('public')->delete($cleanPath);
            }
        }
        $picture->delete();
    }
}
