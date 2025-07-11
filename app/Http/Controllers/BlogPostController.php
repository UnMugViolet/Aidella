<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $blogPosts = BlogPost::select(['id', 'title', 'slug', 'category_id', 'published_at', 'author_id', 'dog_race_id'])
            ->with([
                'category:id,name,slug',
                'author:id,name',
                'dogRace:id,name',
                'attachments',
            ])
            ->whereNotNull('category_id')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        if ($request->ajax()) {
            return response()->json($blogPosts);
        }

        return view('blog_posts', [
            'blogPosts' => $blogPosts,
        ]);
    }

    /**
     * Display a listing of the blog posts.
     *
     * @return \Illuminate\View\View
     */
    public function show($category, $slug)
    {
        $categoryModel = PostCategory::where('slug', $category)->firstOrFail();

        $post = BlogPost::where('slug', $slug)
            ->where('category_id', $categoryModel->id)
            ->with([
                'attachments',
                'author:id,name',
                'category:id,name,slug',
                'dogRace:id,name',
            ])
            ->firstOrFail();

        return view('single_post', [
            'blogPost' => $post,
        ]);
    }
}
