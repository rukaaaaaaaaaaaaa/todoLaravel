<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $table = 'folders';
    protected $fillable = ['name', 'user_id'];

    public function lists()
    {
        return $this->hasMany(Lists::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
