<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublishModel extends Model
{
    protected $table = 'publish';
    protected $fillable = array('userId', 'text', 'file');
}
