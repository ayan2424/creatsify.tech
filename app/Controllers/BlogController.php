<?php

namespace App\Controllers;

use App\Models\Post;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Blog & Insights Controller
 */
class BlogController
{
    public function index(Request $request): Response
    {
        $posts = Post::allPublished();

        return view('blog/index', [
            'posts' => $posts,
            'meta_title' => 'Engineering & Design Blog | Creatsify',
            'meta_desc' => 'Technical writeups on PHP MVC, SaaS architecture, Flutter mobile apps, and marketplace distribution.'
        ]);
    }

    public function show(Request $request, string $slug): Response
    {
        $post = Post::findBySlug($slug);
        if (!$post) {
            return view('errors/404', ['message' => 'Post not found'])->setStatusCode(404);
        }

        // Increment view count
        Post::incrementViews((int)$post['id']);

        return view('blog/show', [
            'post' => $post,
            'meta_title' => "{$post['title']} | Creatsify Blog",
            'meta_desc' => $post['excerpt']
        ]);
    }
}
