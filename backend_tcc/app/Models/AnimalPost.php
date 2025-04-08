<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        'status',
        'sex',
        'posted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
