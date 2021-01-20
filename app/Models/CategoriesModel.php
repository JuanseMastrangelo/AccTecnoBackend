<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriesModel extends Model
{
    protected $table = 'categories';
    protected $fillable = [ 'id', 'name', 'idParent', 'files' ];
}
