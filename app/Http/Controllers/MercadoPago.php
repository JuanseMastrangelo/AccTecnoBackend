<?php

namespace App\Http\Controllers;

use App\Models\AuthModel;
use App\Models\CartModel;
use Illuminate\Http\Request;
use App\Models\UserLocationsModel;

use MP;
use App\Http\Requests;
use App\Models\SellerModel;

class MercadoPago extends Controller {


    function __construct() {
        MP::sandbox_mode(false);
    }


    // Devuelve datos sobre la compra
    public function createPreference(Request $request) {
        $preferenceData = [
            'items' => $request[0],
            "additional_info" => json_encode($request[1]),
            'shipments' => [
                'mode' => 'not_specified',
                'dimensions' => '10 x 10 x 20, 300',
                'default_shipping_method' => 0,
                'cost'=> $request[2],
                'receiver_address' => [
                    "apartment" => "",
                    "city_name" => "Cipolletti",
                    "state_name" => "Rio Negro",
                    "floor" => "",
                    "street_name" => "Del Trabajador",
                    "street_number" => "2122",
                    "zip_code" => "8324",
                ]
            ],
            'payer' => [
//                "name" => $request[1]['userData']['name'],
//                "email" => $request[1]['userData']['email']."ar", // QUITAR .AR
                "name" => "TETE5445823",
                "email" => "test_user_58294528@testuser.com"
            ],
            "notification_url" => "https://softwareargentina.store/api/notification"
        ];

        return MP::create_preference($preferenceData);
    }

// TEST USER COMPRADOR
//    {
//    "id": 692616119,
//    "nickname": "TETE5445823",
//    "password": "qatest3580",
//    "site_status": "active",
//    "email": "test_user_58294528@testuser.com"
//    }

// TEST USER VENDEDOR
//{
//    "id": 692621623,
//    "nickname": "TETE7142268",
//    "password": "qatest1442",
//    "site_status": "active",
//    "email": "test_user_12047295@testuser.com"
//}

    public function getToken() {
        $token = MP::get_access_token();
        return $token;
    }

    public function notificationIPN(Request $request) {
        $id = $request->get('id');
        $topic = $request->get('topic');
        switch($topic) {
            case "payment":
                $payment = MP::get_payment($id);
                $orderId = $payment['response']['collection']['order_id'];
                $merchant_order = MP::get_MerchantOrder($orderId);
                $this->notifDatabase($merchant_order);
                break;
            case "merchant_order":
                $merchant_order = MP::get_MerchantOrder($id);
                $this->notifDatabase($merchant_order);
                break;
        }
        return $additional_info;
    }

    public function notifDatabase($merchant_order)
    {

        $paid_amount = 0;
        $array = $merchant_order['response']['payments'];
        $array_num = count($array);
        for ($i = 0; $i < $array_num; ++$i){
            $payment = $array[$i];
            if ($payment['status'] == 'approved'){
                $paid_amount += $payment['transaction_amount'];
            }
        }
        $message = "";
        if($paid_amount >= $merchant_order['response']['total_amount']){
            if (count($merchant_order['response']['shipments'])>0) { // The merchant_order has shipments
                if($merchant_order['response']['shipments'][0]->status == "ready_to_ship") {
                    $message = "ready_to_ship";
                }
            } else { // The merchant_order don't has any shipments
                $message = "payed";
            }
        } else {
            $message = "pending";
        }
        $id = $merchant_order['response']['id'];
        $sellItem = SellerModel::where('id', $id);
        $additional_info = json_decode($merchant_order['response']['additional_info'], true);

        
        if ($sellItem->count() > 0) {
            $task = $sellItem->update([
                'status' => $message,
                'userData' => json_encode($additional_info),
                'items' => json_encode($merchant_order['response']['payments']),
                'id' => $id,
                'shipId' => $array_num,
                'userId' => $additional_info['id'],
                'orderDetails' => json_encode($merchant_order['response'])
            ]);
        } else {
            $shipLocation = UserLocationsModel::where('userId', "=", $additional_info['id'])->first();
            $task = SellerModel::create([
                'status' => $message,
                'userData' => $merchant_order['response']['additional_info'],
                'items' => json_encode($merchant_order['response']['payments']),
                'id' => $id,
                'shipId' => $array_num,
                'userId' => $additional_info['id'],
                'shipData' => $shipLocation,
                'orderDetails' => json_encode($merchant_order['response'])
            ]);
        }

        $this->deleteCart($additional_info['id']);


        return $task;
    }

    public function deleteCart($uid)
    {
        $row = CartModel::where('userId', $uid);
        $task = $row->delete();
        return $task;
    }


    public function createShippment() {
        $preferenceData = [];
        return MP::create_shipping($preferenceData);
    }

    public function get_all_merchant_order() {
        $task = SellerModel::all();
        return $task;
    }

    public function get_order_by_mepa() {
        return MP::get_all_merchant_order();
    }


    public function get_all_merchant_by_user(Request $request) {
        $refreshToken = $request->bearerToken();
        $uid = AuthModel::where('refreshToken', $refreshToken)->first()->uid;
        $task = SellerModel::where('userId', $uid);
        return $task;
    }
}
