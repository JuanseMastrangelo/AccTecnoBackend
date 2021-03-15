<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SellerModel;

class Seller extends Controller
{
    public function getAll()
    {
        $task = SellerModel::all();
        //error_log($task);
        return $task;
    }

    public function add(Request $request) {
        $id = SellerModel::where('id', $id)->first()->id;
        if ($id > 0) {
            $userData = $request->get('userData');
            $userId = $request->get('userId');
            $status = $request->get('status');
            $items = $request->get('items');
            $shipId = $request->get('shipId');
            $shipData = $request->get('shipData');
            $orderDetails = $request->get('orderDetails');
            $task = SellerModel::Create([
                'userData' => $userData,
                'userId' => $userId,
                'status' => $status,
                'items' => $items, 
                'shipId' => $shipId,
                'shipData' => $shipData,
                'orderDetails' => $orderDetails
            ]);
        } else {
            $task = "Ya existe la venta";
        }
        return json_encode($task);
    }


    public function delete($id)
    {
        $row = SellerModel::where('id', $id);
        $task = $row->delete();
        return $task;
    }

    public function update(Request $request) {
        $id = $request->get('id');
        if ($id > 0) {
            $userData = $request->get('userData');
            $userId = $request->get('userId');
            $status = $request->get('status');
            $items = $request->get('items');
            $shipId = $request->get('shipId');
            $shipData = $request->get('shipData');
            $orderDetails = $request->get('orderDetails');
            $task = SellerModel::where('id', $id)->update([
                'userData' => $userData,
                'userId' => $userId,
                'status' => $status,
                'items' => $items, 
                'shipId' => $shipId,
                'shipData' => $shipData,
                'orderDetails' => $orderDetails
            ]);
            return $task;
        } 
        return null;
    }

    public function updateStatus(Request $request) {
        $id = $request->get('id');
        if ($id > 0) {
            $status = $request->get('status');
            $shipId = $request->get('shipId');
            $task = SellerModel::where('id', $id)->update([
                'status' => $status,
                'shipId' => $shipId
            ]);
            return $task;
        } 
        return null;
    }
}
