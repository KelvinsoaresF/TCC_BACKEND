<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
