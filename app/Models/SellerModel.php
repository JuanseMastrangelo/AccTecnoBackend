<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerModel extends Model
{
    protected $table = 'sells';
    protected $fillable = array('id', 'userData', 'status', 'items', 'shipId', 'userId', 'orderDetails', 'shipData');
}
