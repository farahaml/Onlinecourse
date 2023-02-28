<?php

namespace App\Http\Controllers;

//call order model
use App\Order;

use Illuminate\Http\Request;
use Illumiate\Support\Str;

class OrderController extends Controller
{


    //get orders
    public function index(Request $request){
        $userId = $request->input('user_id');
        $orders = Order::query();

        $orders->when($userId, function($query) use ($userId) {
            return $query->where('user_id', '=', $userId);
        });

        return response()->json([
            'status' => 'success',
            'data' => $orders->get()
        ]);
    }

    //create orders
    public function create(Request $request){
        $user = $request->input('user');
        $course = $request->input('course');
    
        $order = Order::create([
            'user_id' => $user['id'],
            'course_id' =>  $course['id']
        ]);

        //transaction details
        $transactionDetails = [
            'order_id' => $order->id.'-'.Str::random(5),
            'gross_amount' => $course['price']
        ];

        //item details
        $itemDetails = [
            [
                'id' => $course['id'],
                'price' => $course['price'],
                'quantity' => 1,
                'name' => $course['name']
                // 'brand' => 'Surya Microsystems',
                // 'category' => 'Online Course'
            ]
            ];

        //customer details
        $customerDetails = [
            'first_name' => $user['name'],
            'email' => $user['email']
        ];

        $midtransParams = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails
        ];

        $midtransSnapUrl = $this->getMidtransSnapUrl($midtransParams);
    
        $order->snap_url = $midtransSnapUrl;

        $order->metadata = [
            'course_id' => $course['id'],
            'course_price' => $course['price'],
            'course_name' => $course['name'],
            'course_thumbnail' => $course['thumbnail'],
            'course_level' => $course['level']
        ];

        $order->save();

        return response()->json([
            'status' => 'success',
            'data' => $order
        ]);
        // return $midtransSnapUrl;
        return response()->json([
            'status'  => 'success',
            'data' => $order
        ]);
    }

    //get midtrans
    private function getMidtransSnapUrl ($params) {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_PRODUCTION');
        \Midtrans\Config::$is3ds = (bool) env('MIDTRANS_3DS');

        $snapUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
        return $snapUrl;
    }
}
