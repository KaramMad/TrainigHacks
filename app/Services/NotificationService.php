<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Google_Client;

class NotificationService
{
    public function sendNotification(string $fcm, array $message)
    {
        $apiUrl = 'https://fcm.googleapis.com/v1/projects/home-workout-24924/messages:send';
        $access_token = Cache::remember('access_token', now()->addHour(), function () use ($apiUrl) {
            $credentialsFilePath = storage_path('app/google-services.json');
            $client = new \Google_Client();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            $token = $client->getAccessToken();

            return $token['access_token'];
        });

    $response = Http::withHeader('Authorization', "Bearer $access_token")->post($apiUrl, $message);
   // dd($response);
    }
    public function sendPreferdTimeNotification(string $fcm)
    {
        $message = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "body" => "your trainig time in 15 minute!",
                    "title" => " training time "
                ]
            ]
        ];

        $this->sendNotification($fcm, $message);
        return $message['message']['notification'];
        //(newSendNotificationService)->sendByFcm($this->fcmToken, $this->message);

    }
    public function SendTrainingNotification(string $fcm)
    {
        $message = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "body" => "This is an SendTrainingNotification notification message!",
                    "title" => "FCM Message1234"
                ]
            ]
        ];
        $this->sendNotification($fcm, $message);
    }
}
