<?php

namespace App\Orchid\Screens;

use App\Models\BlogPost;
use App\Models\PostCategory;
use App\Orchid\Layouts\BlogPostCategoryLayout;
use App\Orchid\Layouts\BlogPostPicturesLayout;
use App\Orchid\Layouts\BlogPostSeoLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Str;
use Orchid\Attachment\Models\Attachment;

class BlogPostScreen extends Screen
{
    private const NULLABLE_STRING_MAX_255 = 'nullable|string|max:255';

    public $name = 'Ajouter un Article';
    public $description = 'Cette page permet d\'ajouter un article.';

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
                'Contenu' => BlogPostCategoryLayout::class,
                'SEO'     => BlogPostSeoLayout::class,
                'Images'  => BlogPostPicturesLayout::class,
            ]),
        ];
    }

    public function save(Request $request)
    {
        $blogPost = $request->get('post');
        $status = $blogPost['status'] ?? 'draft';

        $request->validate([
            'post.title' => 'required|string|unique:blog_posts,title|max:255',
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

        if ($status === 'published') {
            $blogPost['published_at'] = now();
        }
    
        $createdPost = BlogPost::create([
            'title' => $blogPost['title'],
            'status' => $blogPost['status'] ?? 'draft',
            'content' => $blogPost['html'],
            'category_id' => $blogPost['category_id'] ?? $this->getGeneralCategoryId(),
            'dog_race_id' => $blogPost['dog_race_id'] ?? null,
            'author_id' => $blogPost['author_id'] ?? 1,
            'meta_title' => $blogPost['meta_title'] ?? 'Article - ' . $blogPost['title'],
            'meta_description' => $blogPost['meta_description'] ?? 'Description pour l\'article ' . $blogPost['title'],
            'slug' => $blogPost['slug'] ?? Str::slug($blogPost['title']),
            'published_at' => $blogPost['published_at'] ?? null,
        ]);

        // Add the gallery pictures to the post
        $createdPost->attachments()->sync(
            $request->get('post.gallery', []),
            ['group' => 'gallery']
        );

        $this->saveGalleryPictures($createdPost, $blogPost['gallery'] ?? [], $blogPost['title']);

        $createdPost->save();
        Toast::success('Article enregistrée avec succès!');
        return redirect()->route('platform.posts');
    }

    /**
     * Save gallery pictures for the BlogPost.
     */
    private function saveGalleryPictures(BlogPost $post, array $pictures, $altText)
    {
        if (empty($pictures)) {
            return;
        }

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

        foreach ($newPictureIds as $pictureId) {
            $post->attachments()->attach($pictureId);
        }
    }

    private function getGeneralCategoryId(): int
    {
        $generalCategory = PostCategory::where('slug', 'general')->first();
        return $generalCategory ? $generalCategory->id : 0;
    }
}
