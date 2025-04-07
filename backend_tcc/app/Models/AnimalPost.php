<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalPost extends Model
{
    // protected $table
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'cep',
        'contact',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
