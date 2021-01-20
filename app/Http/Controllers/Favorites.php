<?php

namespace App\Http\Controllers;

use App\Models\AuthModel;
use App\Models\FavoritesModel;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class Favorites extends Controller
{
    public function getAll()
    {
        $request = request();
        $refreshToken = $request->bearerToken();
        $uid = AuthModel::where('refreshToken', $refreshToken)->first()->uid;
        $task = FavoritesModel::select(
            DB::raw('products.*')
        )
            ->join('products', 'products.id', '=', 'favorites.postId')
            ->where('favorites.userId', "=", $uid)
            ->orderBy('favorites.id','DESC')
            ->groupBy('favorites.id')
            ->get();
        return $task;
    }

    public function add(Request $request) {
        $refreshToken = $request->bearerToken();
        $uid = AuthModel::where('refreshToken', $refreshToken)->first()->uid;

        $exist = FavoritesModel::where('userId', $uid)->where('postId', $request->get('postId'));
        $postId = $request->get('postId');
        if ($exist->count() == 0) {
            $task = FavoritesModel::create([
                'userId' => $uid,
                'postId' => $postId,
                "created_at" => Carbon::now()->timestamp,
                "updated_at" => Carbon::now()->timestamp
            ]);
        } else {
            $row = FavoritesModel::where('userId', $uid)
                ->where('postId', $postId);
            $task = $row->delete();
        }
        return json_encode($task);
    }


}
