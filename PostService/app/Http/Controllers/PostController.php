<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Post;


class PostController extends Controller
{
    public function createPost(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        //obtenemos los datos del usuario
        $userResponse = Http::get(env('USER_SERVICE_URL') . '/api/users' . $request->user_id);

        if ($userResponse->successful()) {
            $userData = $userResponse->json();

            $post = Post::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json([
                'message' => 'Post creado con éxito',
                'post' => $post,
                'user' => $userData,
            ], 201);
            
        }

        return response()->json(['error' => 'No se pudo obtener los datos del usuario'], 400);

    }

    public function index()
    {
        $posts = Post::all(); // Obtiene todos los posts

        return response()->json($posts);
    }

    // Método para obtener un post específico
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Obtener los detalles del usuario 
        $user = Http::get('http://user-service.local/api/users/' . $post->user_id);
        
        return response()->json([
            'post' => $post,
            'user' => $user->json(),
        ]);
    }

    // Método para actualizar un post
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Validación de los datos a actualizar
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ]);

        // Actualización de los datos del post
        $post->update($validated);

        return response()->json($post);
    }

    // Método para eliminar un post
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Eliminar el post
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
