<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request) {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'cep' => 'required|string',
            'picture' => 'nullable|string'
        ]);

        $user = User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password']),
            'phone' => $validateData['phone'],
            'cep' => $validateData['cep'],
            'picture' => $validateData['picture'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'usuario criado com sucesso',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
}
