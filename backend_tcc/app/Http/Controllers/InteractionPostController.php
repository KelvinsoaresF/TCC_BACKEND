<?php

namespace App\Http\Controllers;

use App\Models\AnimalPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionPostController extends Controller
{
    // SALVAR POST
    public function savePost(string $postId)
    {
        $user = Auth::user();
        $post = AnimalPost::findOrFail($postId);

        try {
            //code..
            if ($user->savedPosts->contains($postId)) {
                // se o post ja estiver salvo sera removido
                $user->savedPosts()->detach($postId);
                return response()->json([
                    'message' => 'post removido dos salvos',
                    'post' => $post
                ]);
            } else {
                // caso contrario sera salvo
                $user->savedPosts()->attach($postId);
                return response()->json([
                    'message' => 'post salvo com sucesso',
                    'post' => $post
                ]);
            }
        } catch (\Throwable $e) {
            # code...
            return response()->json([
                'error' => 'Erro ao salvar postagem',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeSave(string $postId)
    {
        $user = Auth::user();

        try {

            $post = AnimalPost::findOrFail($postId);

            $user->savedPosts()->detach($postId);

            return response()->json([
                'message' => 'post removido dos salvos',
                'post' => $post
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Erro ao remover postagem',
                'message' => $e->getMessage(),
            ], 500);
        }

    }

    public function indexSaves()
    {
        $user = Auth::user();
        try {
            $posts = $user->savedPosts()->with('user')->latest()->get();

            if(!$posts){
                return response()->json([
                    'message' => 'Nenhum post salvo'
                ]);
            }

            return response()->json([
                'message' => 'Posts salvos buscados com sucesso',
                'savedposts' => $posts
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Erro ao salvar postagem',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
