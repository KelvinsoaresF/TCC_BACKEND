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

            if (!$posts) {
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

    public function postLike(string $id)
    {
        $user = Auth::user();
        $post = AnimalPost::findOrFail($id);

        try {
            if ($post->likes->contains($user->id)) {
                // remove post se ja curtido
                $user->likedPosts()->detach($post->id);
                // remove junto a noficação
                $notification = $user->notifications()
                    ->where('animal_post_id', $post->id)
                    ->where('user_id', $user->id)
                    ->delete();

                return response()->json([
                    'message' => 'Post descurtido com sucesso!',
                    'post' => $post,
                    'likes_count' => $post->likes()->count()
                ]);
            } else {
                $user->likedPosts()->attach($post->id);

                $notification = $user->notifications()->create([
                    'animal_post_id' => $post->id,
                    'user_id' => $user->id, // quem curitu
                    'autor_id' => $post->user->id, // dono do post
                    'type' => 'like' // tipo de notificação
                ]);

                return response()->json([
                    'message' => 'Post curtido com sucesso!',
                    'post' => $post,
                    'notification' => $notification,
                    // 'likes_count' => $post->likes()->count()
                ]);

            }
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Erro ao curtir postagem',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getLikes(string $id)
    {
        try {
            $user = Auth::user();
            $post = AnimalPost::findOrFail($id);

            $liked = $post->likes()->where('user_id', $user->id)->exists();

            $likes = $post->likes()->get();

            if ($likes->isEmpty()) {
                return response()->json([
                    'message' => 'Ninguem curtiu este post ainda',
                    'likes_count' => 0,
                ]);
            }

            return response()->json([
                'message' => 'Curtidas buscadas com sucesso',
                'post' => $post,
                'likes_count' => $post->likes()->count(),
                'user_name' => $likes->pluck('name'),
                'liked' => $liked,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Erro ao curtir postagem',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
