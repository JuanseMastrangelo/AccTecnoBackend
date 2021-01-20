<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLocationsModel extends Model
{
    protected $table = 'userlocations';
    protected $fillable = array('userId', 'locations');
}
