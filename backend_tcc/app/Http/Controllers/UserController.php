<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request) {
        try {
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8',
                'phone' => 'required|string',
            ]);

            $user = User::create([
                'name' => $validateData['name'],
                'email' => $validateData['email'],
                'password' => Hash::make($validateData['password']),
                'phone' => $validateData['phone'],
                'cep' => $validateData['cep'] ?? null,
                'picture' => $validateData['picture'] ?? null,
            ]);

            // $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Usuário criado com sucesso',
                'token_type' => 'Bearer',
                'user' => $user,
                // 'token' => $token,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao registrar usuário',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
