<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'animal_post_id',
        'user_id',
        'autor_id',
        'type'
    ];

    //post relacionado a notificação
    public function animalPost()
    {
        return $this->belongsTo(AnimalPost::class, 'animal_post_id');
    }

    //quem criou a notificação
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // pra quem a notificação foi enviada
    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_id');
    }
}
