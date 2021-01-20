<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{
    protected $table = 'products';
    protected $fillable = [ 'id', 'title', 'description', 'count', 'purchaseValue', 'saleValue', 'categorieId', 'subCategorieId', 'high', 'width', 'colour', 'warranty', 'model', 'weight', 'RAM', 'files' ];
}
