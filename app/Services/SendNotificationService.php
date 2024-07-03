<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class UserService
{
    public function sendNotification(string $fcm, array $massage)
    {
        $apiUrl = 'https://fcm.googleapis.com/v1/projects/homeworkoutnoti/messages:send';
        $access_token = Cache::remember('access_token', now()->addHour(), function () use ($apiUrl) {
            $credentialsFilePath = storage_path('app/fcm.json');
            $client = new \Google_Client();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            $token = $client->getAccessToken();

            return $token['access_token'];
        });

        $message = [
            "message" => [
                //fcm from front
                "token" => "dx4YAH7PKJo4ju-wPauNqM:APA91bFggx26kpt0R5xWwPs5xTrdIytQbUCqx1Hsbi2QGuMdqP1x24WsmnfY8kH0Gfj0dJHIWd2s3q9QGxpxN1185xKnRk7-At9_EQ00gGUdwOnV0EUYxMb7dfKEGyHBLgEEIVo_fZZU",
                "notification" => [
                    "body" => "This is an FCM notification message!",
                    "title" => "FCM Message1234"
                ]
            ]
        ];

        $response = Http::withHeader('Authorization', "Bearer $access_token")->post($apiUrl, $message);
    }
}
