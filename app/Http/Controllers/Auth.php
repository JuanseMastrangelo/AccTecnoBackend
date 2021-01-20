<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthModel;

class Auth extends Controller
{

    public function add(Request $request) {
        $uid = $request->get('uid');
        $exist = AuthModel::where('uid', $uid)->count();
        if($exist > 0) {
            $token = $request->get('refreshToken');
            $task = AuthModel::where('uid', $uid)
                ->update(['refreshToken' => $token]);
        } else {
            $task = AuthModel::create([
                'uid' => $request->uid,
                'displayName' => $request->displayName,
                'email' => $request->email,
                'photoURL' => $request->photoURL,
                'refreshToken' => $request->refreshToken,
                'rol' => 0
            ]);
        }
        return $task;
    }

    public function get_by_id($uid) {
        $task = AuthModel::find($uid);
        return $task;
    }

    public function getAllCount() {
        $task = AuthModel::all();
        return $task->count();
    }
}
