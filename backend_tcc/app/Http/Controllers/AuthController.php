<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    public function register(Request $request)
    {
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
                'picture' => $validateData['picture'] ?? null,
            ]);

            $token = $user->createToken($user->name)->plainTextToken;

            return response()->json([
                'message' => 'Usuário criado com sucesso',
                'token_type' => 'Bearer',
                'user' => $user,
                'token' => $token,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao registrar usuário',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validateData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt($validateData)) {
                return response()->json([
                    'error' => 'Credenciais inválidas',
                    'status' => 'error'
                ]);
            }

            $user = User::where('email', $validateData['email'])->first();

            $token = $user->createToken($user->name)->plainTextToken;
            return response()->json([
                'message' => 'Login realizado com sucesso',
                'token_type' => 'Bearer',
                'user' => $user,
                'token' => $token,
                'status' => 'success'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao realizar login',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout realizado com sucesso',
            'status' => 'success'
        ]);
    }
}
