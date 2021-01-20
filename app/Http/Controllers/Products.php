<?php

namespace App\Http\Controllers;

use App\Models\AuthModel;
use Illuminate\Http\Request;
use App\Models\ProductsModel;
use App\Models\Files;
use Illuminate\Support\Facades\DB;

class Products extends Controller
{

    public function getAll()
    {

        $task = ProductsModel::select(
            DB::raw('categories.name as nombreCategoria'),
            DB::raw('products.*')
            )
            ->leftjoin('categories', 'categories.id', '=', 'products.subCategorieId')
            ->groupBy('products.id')
            ->get();
        return $task;
    }

    public function search(String $value)
    {
        if ($value === '$'){
            $task = ProductsModel::all();
        } else {
            $task = ProductsModel::where('title', 'LIKE', "%$value%")
                ->orWhere('description', 'LIKE', "%$value%")
                ->orWhere('model', 'LIKE', "%$value%")
                ->get();
        }
        return $task;
    }

    public function add(Request $request) {
        $id = $request->get('id');
        if ($id > 0) {
            $title = $request->get('title');
            $description = $request->get('description');
            $count = $request->get('count');
            $purchaseValue = $request->get('purchaseValue');
            $saleValue = $request->get('saleValue');
            $categorieId = $request->get('categorieId');
            $subCategorieId = $request->get('subCategorieId');
            $high = $request->get('high');
            $width = $request->get('width');
            $colour = $request->get('colour');
            $warranty = $request->get('warranty');
            $model = $request->get('model');
            $weight = $request->get('weight');
            $RAM = $request->get('RAM');
            $files = $request->get('files');
            $task = ProductsModel::where('id', $id)
                ->update([
                    'title' => $title,
                    'description' => $description,
                    'count' => $count,
                    'purchaseValue' => $purchaseValue,
                    'saleValue' => $saleValue,
                    'categorieId' => $categorieId,
                    'subCategorieId' => $subCategorieId,
                    'high' => $high,
                    'width' => $width,
                    'colour' => $colour,
                    'warranty' => $warranty,
                    'model' => $model,
                    'weight' => $weight,
                    'RAM' => $RAM,
                    'files' => $files,
                ]);
        } else {
            $task = ProductsModel::create($request->all());
        }
        // $this->addImage($request->get('files'));
        return $files;
    }

    // Almacena archivo de base64 en el servidor fisicamente
    public function addImage($files) {
        $i = 0;
        $fileList = json_decode($files);
        foreach ($fileList as $file) {
            $fileClean = str_replace('data:image/jpeg;base64,', '', $fileList[$i]->{'path'});
            $file = base64_decode($fileClean);
            $safeName = 'prueba.png';
            $success = file_put_contents(public_path('productsImages').'/'.$safeName, $file);
            $i++;
        }
        return $i;
    }


    public function delete($id)
    {
        $row = ProductsModel::find($id);
        $task = $row->delete();
        return $task;
    }


    public function agotados() {
        $task = ProductsModel::where('count', '<=', '2')->get();
        return $task;
    }

    public function news() {
        $task = ProductsModel::paginate(15);
        return $task;
    }

    public function productById($id) {
        $request = request();
        $refreshToken = $request->bearerToken();
        $uid = AuthModel::where('refreshToken', $refreshToken)->first()->uid;

        $task = ProductsModel::select(
            DB::raw('products.*'),
            DB::raw('(CASE WHEN cart.userId = ' . $uid . ' THEN true ELSE false END) AS in_cart'),
            DB::raw('(CASE WHEN favorites.userId = ' . $uid . ' THEN true ELSE false END) AS is_favorite')
        )
            ->leftjoin('cart', 'cart.postId', '=', 'products.id')
            ->leftjoin('favorites', 'favorites.postId', '=', 'products.id')
            ->where('products.categorieId', '=', $id)
            ->orWhere('products.subCategorieId', '=', $id)
            ->orderBy('products.id','DESC')
            ->groupBy('products.id')
            ->get();
        return $task;
    }


}
