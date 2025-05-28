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
        'state',
        'city',
        'contact',
        'status',
        'sex',
        'age',
        'posted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function savedByPost()
    {
        return $this->belongsToMany(User::class, 'saved_posts')->withTimestamps();
    }
}
