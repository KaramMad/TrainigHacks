<?php

namespace App\Services;

use App\Models\Notification;
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
            $credentialsFilePath = storage_path('app/fcm.json');
            $client = new \Google_Client();
            $client->setAuthConfig($credentialsFilePath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            $token = $client->getAccessToken();

            return $token['access_token'];
        });

        $response = Http::withHeader('Authorization', "Bearer $access_token")->post($apiUrl, $message);

        $notification = Notification::create([
            'title' => $message['message']['notification']['title'],
            'body' => $message['message']['notification']['body'],

        ]);

        $user = User::where('fcm_token', $fcm)->first();
        if ($user) {
            $user->notifications()->attach($notification->id);
        }

        return $response;
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
    public function SendTrainingNotification(string $fcm,string $body,string $title)
    {
        $message = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "body" => $body,
                    "title" => $title,
                ]
            ]
        ];
        $this->sendNotification($fcm, $message);
    }
}
