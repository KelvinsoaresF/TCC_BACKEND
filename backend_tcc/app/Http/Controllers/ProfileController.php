<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'picture' => $user->picture,
        ]);
    }

    public function myPosts()
    {
        $user = Auth::user();
        $posts = $user->animalPosts()->with('user')->get();

        if($posts->isEmpty()) {
            return response()->json([
                'error' => 'Você não tem permissão para acessar esses posts',
            ], 403);
        } else {
            return response()->json([
                'posts' => $posts,
                'userId' => $user->id,
            ]);
        }
    }

    public function editProfile(Request $request)
    {
        $user = Auth::user();

        try{
            $validateData = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'password' => 'nullable|string|min:8',
                'phone' => 'nullable|string',
                'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('picture')) {
                $picture = $request->file('picture')->store('profile_pictures', 'public');
                $user->picture = $picture;
            }

            $user->update([
                'name' => $validateData['name'] ?? $user->name,
                'email' => $validateData['email'] ?? $user->email,
                'password' => isset($validateData['password']) ? Hash::make($validateData['password']) : $user->password,
                'phone' => $validateData['phone'] ?? $user->phone,
                'picture' => $user->picture,
            ]);

            return response()->json([
                'message' => 'Perfil editado com sucesso',
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao editar perfil',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
