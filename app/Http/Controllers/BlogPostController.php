<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function index()
    {
        $blogPosts = BlogPost::select(['id', 'title', 'slug', 'category_id', 'published_at', 'author_id', 'dog_race_id'])
            ->with([
                'category:id,name,slug',
                'author:id,name',
                'dogRace:id,name',
                'pictures',
            ])
            ->whereNotNull('category_id')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

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
                'pictures',
            ])
            ->firstOrFail();

        return view('blog_post', [
            'blogPost' => $post,
        ]);
    }
}
