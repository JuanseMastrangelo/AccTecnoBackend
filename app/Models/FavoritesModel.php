<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoritesModel extends Model
{
    protected $table = 'favorites';
    protected $fillable = [ 'userId', 'postId' ];
}
