<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentLog;
use App\Order;

class WebhookController extends Controller
{
    public function midtransHandler(Request $request){
        $data = $request->all();

        //get data from body
        $signatureKey = $data['signature_key'];
        $orderId = $data['order_id'];
        $statusCode = $data['status_code'];
        $grossAmount = $data['gross_amount'];
        $serverKey = env('MIDTRANS_SERVER_KEY');

        //create our signature key
        $mySignatureKey = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        //get data from body
        $transactionStatus = $data['transaction_status'];
        $paymentType = $data['payment_type'];
        $fraudStatus = $data['fraud_status'];

        //validating signature key
        if ($signatureKey !== $mySignatureKey) {
            return response()->json([ 
                //error response
                'status' => 'error',
                'message' => 'invalid signature key'
                ], 400);
        }

        //parting order Id
        $realOrderId = explode('-', $orderId);
        //finding real order Id
        $order = Order::find($realOrderId[0]);

        //if order Id not found
        if (!$order) {
            return response()->json([
                //error response
                'status' => 'error',
                'message' => 'order id not found'
            ], 404);
        }

        //denied webhook, without changing order data
        if ($order === 'success') {
            return response()->json([
                'status' => 'error',
                'message' => 'operation not permitted'
            ], 405);
        }

        //checking transaction status
        if ($transactionStatus == 'capture'){
            if ($fraudStatus == 'challenge'){
                // TODO set transaction status on your database to 'challenge'
                // and response with 200 OK
                $order->status = 'challenge';
            } else if ($fraudStatus == 'accept'){
                // TODO set transaction status on your database to 'success'
                // and response with 200 OK
                $order->status = 'success';
            }
        } else if ($transactionStatus == 'settlement'){
            // TODO set transaction status on your database to 'success'
            // and response with 200 OK
            $order->status = 'success';
        } else if ($transactionStatus == 'cancel' ||
          $transactionStatus == 'deny' ||
          $transactionStatus == 'expire'){
          // TODO set transaction status on your database to 'failure'
          // and response with 200 OK
          $order->status = 'failure';
        } else if ($transactionStatus == 'pending'){
          // TODO set transaction status on your database to 'pending' / waiting payment
          // and response with 200 OK
          $order->status = 'pending';
        }

        //saving payment logs
        $logData = [
            'status' => $transactionStatus,
            'raw_response' => json_encode($data),
            'order_id' => $realOrderId[0],
            'payment_type' => $paymentType
        ];

        PaymentLog::create($logData);
        $order->save();

        //give premium class access
        if ($order->status === 'success') {
            createPremiumAccess ([
                'user_id' => $order->user_id,
                'course_id' => $order->course_id
            ]);
        }

        //success response
        return response()->json('ok');
        // return true;
    }
}
