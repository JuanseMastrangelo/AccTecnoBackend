<?php

namespace App\Http\Controllers;

use App\Models\AuthModel;
use App\Models\UserLocationsModel;
use Illuminate\Http\Request;

class UserLocations extends Controller
{
    public function getAll()
    {
        $request = request();
        $refreshToken = $request->bearerToken();
        $uid = AuthModel::where('refreshToken', $refreshToken)->first()->uid;
        $task = UserLocationsModel::where('userId', "=", $uid)->get();
        return $task;
    }

    public function add(Request $request) {
        $refreshToken = $request->bearerToken();
        $uid = AuthModel::where('refreshToken', $refreshToken)->first()->uid;

        $exist = UserLocationsModel::where('userId', $uid);
        $locations = $request->get('locations');
        if ($exist->count() == 0) {
            UserLocationsModel::create([
                'userId' => $uid,
                'locations' => json_encode($locations)
            ]);
        } else {
            $exist->update(['locations' => json_encode($locations)]);
        }
        return $locations;
    }
}
