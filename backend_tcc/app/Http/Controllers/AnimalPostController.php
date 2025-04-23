<?php

namespace App\Http\Controllers;

use App\Models\AnimalPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AnimalPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $posts = AnimalPost::with('user')->get();
        return response()->json([
            "message" => "posts carregados",
            "posts" => $posts
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $status = $request->input('status', 'disponivel');

            $validateData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'cep' => 'nullable|string|max:10',
                'category' => 'required|string|max:50',
                'sex' => 'required|string|max:10',
                'age' => 'nullable|string|max:10',
                'contact' => 'nullable|string|max:50',

            ]);

            $validateData['status'] = $status;
            $validateData['user_id'] = Auth::id();
            $validateData['posted_at'] = now();

            $post = AnimalPost::create($validateData);
            return response()->json([
                'message' => 'Postagem criada com sucesso',
                'post' => $post,
                'userId' => $validateData['user_id'],
            ]);

        } catch (\Throwable $e) {
            # code...
            return response()->json([
                'error' => 'Erro ao criar postagem',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = AnimalPost::with('user')->findOrFail($id);

            return response()->json([
            'message' => 'post buscado com sucesso',
            'post' => $post,
        ]);
        } catch (\Throwable $e) {
            # code...
            return response()->json([
                'error' => 'Erro ao buscar postagem',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $post = AnimalPost::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Você não tem permissão para editar este post',
            ], 403);
        }

        $validateData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cep' => 'nullable|string|max:10',
            'category' => 'required|string|max:50',
            'sex' => 'required|string|max:10',
            'age' => 'nullable|string|max:10',
            'contact' => 'nullable|string|max:50',
            'status' => 'in:disponivel,adotado',
        ]);

        $post->update($validateData);
        return response()->json([
            'message' => 'Postagem atualizada com sucesso',
            'post' => $post,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = AnimalPost::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Você não tem permissão para excluir este post',
            ], 403);
        }
        $post->delete();
        return response()->json([
            'message' => 'Postagem excluída com sucesso',
        ]);
    }
}
