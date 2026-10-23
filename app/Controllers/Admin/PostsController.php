<?php

namespace App\Controllers\Admin;

use App\Models\Post;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Blog Posts Manager
 */
class PostsController
{
    public function index(Request $request): Response
    {
        $posts = Post::all();

        return view('admin/posts/index', [
            'posts' => $posts,
            'meta_title' => 'Blog Posts | Creatsify Admin'
        ]);
    }

    public function create(Request $request): Response
    {
        return view('admin/posts/create', [
            'meta_title' => 'New Blog Post | Creatsify Admin'
        ]);
    }

    public function store(Request $request): Response
    {
        $title = trim((string)$request->input('title', ''));
        $slug = trim((string)$request->input('slug', '')) ?: strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
        $excerpt = trim((string)$request->input('excerpt', ''));
        $content = trim((string)$request->input('content', ''));
        $coverImage = trim((string)$request->input('cover_image', '/assets/brand/creatsify-icon.png'));
        $category = trim((string)$request->input('category', 'Engineering'));
        $isPublished = (int)$request->input('is_published', 1);

        if (empty($title)) {
            flash('error', 'Post title is required.');
            return Response::redirect('/admin/posts/create');
        }

        $id = Post::create([
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'cover_image' => $coverImage,
            'category' => $category,
            'author_id' => auth_id(),
            'is_published' => $isPublished,
            'published_at' => date('Y-m-d H:i:s')
        ]);

        audit_log('create_post', 'post', $id, "Created blog post: {$title}");
        flash('success', "Post '{$title}' created successfully!");

        return Response::redirect('/admin/posts');
    }

    public function edit(Request $request, string $id): Response
    {
        $post = Post::findById((int)$id);
        if (!$post) {
            flash('error', 'Post not found.');
            return Response::redirect('/admin/posts');
        }

        return view('admin/posts/edit', [
            'post' => $post,
            'meta_title' => "Edit {$post['title']} | Creatsify Admin"
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $title = trim((string)$request->input('title', ''));
        $slug = trim((string)$request->input('slug', ''));
        $excerpt = trim((string)$request->input('excerpt', ''));
        $content = trim((string)$request->input('content', ''));
        $coverImage = trim((string)$request->input('cover_image', '/assets/brand/creatsify-icon.png'));
        $category = trim((string)$request->input('category', 'Engineering'));
        $isPublished = (int)$request->input('is_published', 1);

        Post::update((int)$id, [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'cover_image' => $coverImage,
            'category' => $category,
            'is_published' => $isPublished
        ]);

        audit_log('update_post', 'post', (int)$id, "Updated post: {$title}");
        flash('success', "Post updated successfully!");

        return Response::redirect('/admin/posts');
    }

    public function toggle(Request $request, string $id): Response
    {
        $newState = Post::togglePublish((int)$id);
        audit_log('toggle_post', 'post', (int)$id, "Toggled post publish state to " . ($newState ? 'LIVE' : 'DRAFT'));
        flash('success', 'Post publish status updated.');
        return Response::redirect('/admin/posts');
    }

    public function delete(Request $request, string $id): Response
    {
        Post::delete((int)$id);
        audit_log('delete_post', 'post', (int)$id, "Deleted post #{$id}");
        flash('success', 'Post deleted successfully.');
        return Response::redirect('/admin/posts');
    }
}
