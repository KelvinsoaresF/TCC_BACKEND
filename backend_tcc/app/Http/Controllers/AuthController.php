<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validateData = $request->validate(
                [
                'name' => 'required|string|max:255|min:3',
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8',
                'phone' => 'required|string',
                'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ],
                [
                    'name.required' => 'O nome é obrigatório.',
                    'name.string' => 'O nome deve ser um texto.',
                    'name.min' => 'O nome deve ter pelo menos :min caracteres.',

                    'email.required' => 'O email é obrigatório.',
                    'email.email' => 'O email deve ser um endereço de email válido.',

                    'password.required' => 'A senha é obrigatória.',
                    'password.min' => 'A senha deve ter pelo menos :min caracteres.',

                    'phone.required' => 'O telefone é obrigatório.',

                ]
        );

            if ($request->hasFile('picture')) {
                $picture = $request->file('picture')->store('profile_pictures', 'public');
            } else {
                $picture = null;
            }

            $user = User::create([
                'name' => $validateData['name'],
                'email' => $validateData['email'],
                'password' => Hash::make($validateData['password']),
                'phone' => $validateData['phone'],
                'picture' => $picture,
            ]);

            // $token = $user->createToken($user->name)->plainTextToken;

            return response()->json([
                'message' => 'Usuário criado com sucesso!',
                'token_type' => 'Bearer',
                'user' => $user,
                // 'token' => $token,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Dados invalidos',
                'errors' => $e->errors()
            ], 422); // servidor recebe a requisição, mas os dados estão invalidos
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao registrar usuário :(',
                'info' => $e->getMessage(),
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
                'message' => 'Login realizado com sucesso!',
                'token_type' => 'Bearer',
                'user' => $user,
                'token' => $token,
                'status' => 'success'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao realizar login, tente novamente mais tarde.',
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
