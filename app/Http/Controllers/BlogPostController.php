<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function index()
    {
        $blogPosts = BlogPost::with(['category', 'pictures', 'author', 'dogRace'])
            ->whereNotNull('category_id')
            ->orderBy('created_at', 'desc')
            ->where('status', 'published')
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
            ->firstOrFail();

        return view('blog_post', [
            'blogPost' => $post,
        ]);
    }
}
