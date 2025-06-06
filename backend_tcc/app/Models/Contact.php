<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'receiver_id', //remetente
        'sender_id', //destinatário
        'animal_post_id',
        'message'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_user');
    }

    public function post()
    {
        return $this->belongsTo(AnimalPost::class, 'animal_post_id');
    }
}
