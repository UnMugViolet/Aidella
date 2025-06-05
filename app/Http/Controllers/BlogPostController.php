<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function index()
    {
        $blogPosts = BlogPost::with(['category', 'pictures'])
            ->whereNotNull('category_id')
            ->orderBy('created_at', 'desc')
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
    public function show($slug)
    {
        $blogPost = BlogPost::with(['category', 'pictures'])
            ->where('slug', $slug)
            ->whereNotNull('category_id')
            ->firstOrFail();

        return view('blog_post', [
            'blogPost' => $blogPost,
        ]);
    }
}
