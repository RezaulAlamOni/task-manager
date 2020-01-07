<?php

namespace App\Http\Controllers;

use App\FcmToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FirbaseController extends Controller
{
    protected $FIREBASE_API_KEY = 'AIzaSyAptOmWCzJ3Xrh0H--BKWYynTIEIJQbCvM';
    protected $FIREBASE_SERVER_KEY = 'AAAARcV7K7s:APA91bH0ftvB10cmwh2bFK4LncSz81FN1vDMxbB6xkEsMB76Z1R3EEYAPa2LZ-CfEAeAd83P5bSAILsjyCyebdtLzzWddcFZXrv3sU5aR3zQWqQs7prXa3ow3qB3i8_aoGHXeFMTtU4f';

    public function send ($registration_ids, $message)
    {
        $fields = array(
//            'to' => $registration_ids,
            'registration_ids' => $registration_ids, // array send to registration_ids
            'notification' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    public function sendPushNotification ($post)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $headers = [
            'Authorization:key=' . $this->FIREBASE_SERVER_KEY,
            'Content-Type:application/json'
        ];
//        $post['data']['tag'] = [];
//        $post['data']['tag'] = uniqid();
//        $curl = curl_init();
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => "",
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 30,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_POSTFIELDS => json_encode($post, true),
//            CURLOPT_HTTPHEADER => $headers
//        ));
//        $response = curl_exec($curl);
//        $err = curl_error($curl);
//        curl_close($curl);
//        if ($err) {
//            return $err;
//        } else {
//            return $response;
//        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);
//
//        return true;
    }

    public function AddFcmToken (Request $request)
    {
        $user_id = $request->user_id;
        $fcm_token = $request->fcm_token;

        $check = FcmToken::where('user_id', $user_id)->first();
        if ($check) {
            FcmToken::where('user_id', $user_id)->update(['user_id' => $user_id, 'fcm_token' => $fcm_token]);
        } else {
            FcmToken::create(['user_id' => $user_id, 'fcm_token' => $fcm_token]);
        }
        // $okens =  FcmToken::all()->pluck('fcm_token')->toArray();
        // $data = [
        //     "title" => "FCM Registration Successful",
        //     "body" => 'Test Notification',
        //
        // ];
        // $this->send($okens, $data);

        return response()->json(['status' => 'success']);
    }
}
