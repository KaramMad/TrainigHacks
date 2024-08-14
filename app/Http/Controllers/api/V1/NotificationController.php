<?php

namespace App\Http\Controllers\api\V1;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use App\Services\NotificationService;
use App\Http\Requests\NotificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\ImageTrait;
use Carbon\Carbon;

class NotificationController extends Controller
{

    public function __construct(protected NotificationService $service)
    {
    }
    public function sendPreferdTimeNotification()
    {

        $users = User::get(['fcm_token', 'id']);

        foreach ($users as $user) {
            $message = $this->service->sendPreferdTimeNotification($user->fcm_token);
        }
        $message = Notification::create(
            [
                'data' => $message,
                'type' => 'Preferd'
            ]
        );
        $message->users()->attach($users, ['created_at' => now(), 'updated_at' => now()]);
    }
    public function sendTrainingNotification()
    {
        $users = User::get(['fcm_token', 'id']);
        foreach ($users as $user) {
            $message = $this->service->sendTrainingNotification($user->fcm_token);
        }
        $message = Notification::create(
            [
                'data' => $message,
                'type' => 'Training day'
            ]
        );
        $message->users()->attach($users, ['created_at' => now(), 'updated_at' => now()]);
    }

    // public function updateToken(NotificationRequest $request)
    // {
    //     $data = $request->validated();
    //     $user = Auth::user();
    //     $user->update(['fcm_token' => $request->fcm_token]);
    //     return response()->json(['message' => 'Updated Successfully.']);
    // }

    public function getAllNotifications()
    {
        $user = Auth::user();
        return $user->load('notifications')->notifications;

    }

    public function sendPreferdTime(NotificationRequest $request )
    {
        $data = $request->validated('fcm_token');

        $this->service->sendPreferdTimeNotification($data);
        return response()->json([
            'message' => 'send notifiction seccessfuly'
        ]);

    }
    public function sendTrainingDay(NotificationRequest $request)
    {
        $data = $request->validated('fcm_token');

        $this->service->sendTrainingNotification($data,"hamoda","souad");
        return response()->json([
            'message' => 'send notifiction seccessfuly'
        ]);



    }
}
