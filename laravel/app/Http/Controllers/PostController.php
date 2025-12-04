<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Post::with(['user', 'category'])->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $post = Post::create([
            'title'       => $validated['title'],
            'content'     => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id'     => $request->user()->id, // otomatis ambil user login
        ]);

        return response()->json(['message' => 'Post created', 'data' => $post]);
    }

    public function show($id)
    {
        return response()->json([
            'data' => Post::with(['user', 'category'])->findOrFail($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // hanya user pemilik atau admin
        if ($request->user()->id !== $post->user_id && !$request->user()->is_admin) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $post->update($request->all());

        return response()->json(['message' => 'Updated', 'data' => $post]);
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // hanya user pemilik atau admin
        if ($request->user()->id !== $post->user_id && !$request->user()->is_admin) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
