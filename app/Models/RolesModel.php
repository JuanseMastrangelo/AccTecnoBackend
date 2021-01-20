<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolesModel extends Model
{
    protected $table = 'roles';
    protected $fillable = array('name', 'level');
}
