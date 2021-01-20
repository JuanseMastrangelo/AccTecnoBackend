<?php

namespace App\Http\Controllers;

use App\Models\AuthModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CartModel;
use DB;

class Cart extends Controller
{
    public function getAll()
    {
        $request = request();
        $refreshToken = $request->bearerToken();
        $uid = AuthModel::where('refreshToken', $refreshToken)->first()->uid;
        $task = CartModel::select(
            DB::raw('products.*')
        )
            ->join('products', 'products.id', '=', 'cart.postId')
            ->where('cart.userId', "=", $uid)
            ->orderBy('cart.id','DESC')
            ->groupBy('cart.id')
            ->get();
        return $task;
    }

    public function add(Request $request) {
        $refreshToken = $request->bearerToken();
        $uid = AuthModel::where('refreshToken', $refreshToken)->first()->uid;

        $exist = CartModel::where('userId', $uid)->where('postId', $request->get('postId'));
        if ($exist->count() == 0) {
            $postId = $request->get('postId');
            $task = CartModel::create([
                'userId' => $uid,
                'postId' => $postId,
                "created_at" => Carbon::now()->timestamp,
                "updated_at" => Carbon::now()->timestamp
            ]);
        } else {
            $task = "Ya existe en el carro";
        }
        return json_encode($task);
    }


    public function delete($id)
    {
        $request = request();
        $refreshToken = $request->bearerToken();
        $uid = AuthModel::where('refreshToken', $refreshToken)->first()->uid;

        $row = CartModel::where('userId', $uid)
            ->where('postId', $id);
        $task = $row->delete();
        return $task;
    }
}
