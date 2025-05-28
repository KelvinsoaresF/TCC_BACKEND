<?php

namespace App\Http\Controllers;

use App\Models\AnimalPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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

            $validateData = $request->validate(
                [

                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'cep' => 'nullable|string|max:12',

                    'state' => 'nullable|string|max:255',
                    'city' => 'nullable|string|max:255',

                    'category' => 'required|string|max:50',
                    'sex' => 'required|string|max:10',
                    'age' => 'nullable|string|max:10',
                    'contact' => 'nullable|string|max:50',
                ],
                [
                    'title.required' => 'O título é obrigatório.',
                    'title.string' => 'O título deve ser um texto.',
                    'title.max' => 'O título não pode ter mais que :max caracteres.',

                    'description.string' => 'A descrição deve ser um texto.',

                    'cep.string' => 'O CEP deve ser um texto.',
                    'cep.max' => 'O CEP não pode ter mais que :max caracteres.',

                    'state.string' => 'O estado deve ser um texto.',
                    'city.max' => 'O cidade não pode ter mais que :max caracteres.',

                    'state.string' => 'O estado deve ser um texto.',
                    'state.max' => 'O estado não pode ter mais que :max caracteres.',
                    'state.required' => 'O estado é obrigatório.',

                    'city.string' => 'A cidade deve ser um texto.',
                    'city.max' => 'A cidade não pode ter mais que :max caracteres.',
                    'city.required' => 'A cidade é obrigatória.',

                    'category.required' => 'A categoria é obrigatória.',
                    'category.string' => 'A categoria deve ser um texto.',
                    'category.max' => 'A categoria não pode ter mais que :max caracteres.',

                    'sex.required' => 'O sexo é obrigatório.',
                    'sex.string' => 'O sexo deve ser um texto.',
                    'sex.max' => 'O sexo não pode ter mais que :max caracteres.',

                    'age.string' => 'A idade deve ser um texto.',
                    'age.max' => 'A idade não pode ter mais que :max caracteres.',

                    'contact.string' => 'O contato deve ser um texto.',
                    'contact.max' => 'O contato não pode ter mais que :max caracteres.',
                ]
            );

            if ($request->hasFile('picture')) {
                $picture = $request->file('picture')->store('pictures', 'public');
            } else {
                $picture = null;
            }

            $validateData['status'] = $status;
            $validateData['user_id'] = Auth::id();
            $validateData['posted_at'] = now();
            $validateData['picture'] = $picture;

            $post = AnimalPost::create($validateData);
            return response()->json([
                'message' => 'Postagem criada com sucesso',
                'post' => $post,
                'userId' => $validateData['user_id'],
            ]);
        } catch (ValidationException $e) {
            // Captura ERROS DE VALIDAÇÃO e retorna no formato padrão do Laravel
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
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

    //  função editar post não pronta, precisa de ajustes.
    public function update(Request $request, string $id)
    {
        $post = AnimalPost::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Você não tem permissão para editar este post',
            ], 403);
        }

        $validateData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'cep' => 'sometimes|nullable|string|max:10',
            'category' => 'sometimes|string|max:50',
            'sex' => 'sometimes|string|max:10',
            'age' => 'sometimes|nullable|string|max:10',
            'contact' => 'sometimes|nullable|string|max:50',
            'status' => 'sometimes|in:disponivel,adotado',
            'city' => 'sometimes|nullable|string|max:100',
            'state' => 'sometimes|nullable|string|max:100',
        ]);

        try {
            $post->update($validateData);
            return response()->json([
                'message' => 'Postagem atualizada com sucesso',
                'post' => $post,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Erro ao editar perfil',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // $post->update($validateData

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
