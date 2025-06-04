<?php

namespace App\Http\Controllers;

use App\Models\AnimalPost;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function indexLikes()
    {
        $user = Auth::user();

        $notifications = Notification::where('autor_id', $user->id)->
        where('type', 'like')
        ->with(['user', 'animalPost' ]) // retorna o post curtido e o usuario que curtiu
        ->orderBy('created_at', 'desc')
        ->get();

        if ($notifications->empty()) {
            return response()->json([
                'message' => 'Nenhum notificação encontrada',
            ]);
        }

        return response()->json([
            'notifications' => $notifications,
        ]);
    }
}
