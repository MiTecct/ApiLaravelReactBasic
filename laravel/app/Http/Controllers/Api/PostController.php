<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * GET /api/posts
     * Ambil semua post
     */
    public function index()
    {
        $posts = Post::with('category', 'user')->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $posts
        ]);
    }

    /**
     * POST /api/posts
     * Tambah post baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Ambil user yang login
        $validated['user_id'] = auth()->id();

        $post = Post::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Post berhasil dibuat',
            'data' => $post
        ], 201);
    }

    /**
     * GET /api/posts/{id}
     * Ambil detail post
     */
    public function show($id)
    {
        $post = Post::with('category', 'user')->find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $post
        ]);
    }

    /**
     * PUT /api/posts/{id}
     * Update post
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'content'     => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id'
        ]);

        // hanya pemilik post boleh update
        if ($post->user_id !== auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak diizinkan'
            ], 403);
        }

        $post->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Post berhasil diperbarui',
            'data' => $post
        ]);
    }

    /**
     * DELETE /api/posts/{id}
     * Hapus post
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post tidak ditemukan'
            ], 404);
        }

        if ($post->user_id !== auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Tidak diizinkan'
            ], 403);
        }

        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Post berhasil dihapus'
        ]);
    }
}
