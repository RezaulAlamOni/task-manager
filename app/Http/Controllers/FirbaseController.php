<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FirbaseController extends Controller
{
    protected $FIREBASE_API_KEY = 'AIzaSyAptOmWCzJ3Xrh0H--BKWYynTIEIJQbCvM';
    public function send($registration_ids, $message) {
        $fields = array(
            'to' => $registration_ids,
            'registration_ids' => $registration_ids,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }
    public function sendPushNotification($fields)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $headers = [
            'Authorization: key='.$this->FIREBASE_API_KEY,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);

        return true;
    }

    public function AddFcmToken(Request $request){
        
    }
}
