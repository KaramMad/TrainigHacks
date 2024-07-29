<?php

namespace App\Http\Controllers\api\V1;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\NotificationService;
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

        // $fcm='c7Q3JipL4pQ6IjMjFRKzQq:APA91bFXXhhW7y1xXI7xUGijQxfcBEzS--E6X9lP4AbXO1Q0mfGvXka2llQzq2MSYlaqyM1sehVl5Tn8yWastSGpzgmNZsQ81h9iMu1sjgcP9my59nyGc20KHYAL3frmOcr-aM32XxWO';
        $fcm = 'dahhRhLATaa6gVw9UOFgD8:APA91bEMwHUxLej03L3ur8kJfzkxSoPE_Q-w1ROT6r4VOzQzYV4TZP5_bQgh5vRbhZcYYXFXNvbmMEiME01ihIsFv8STC8EkCuc-FINQgq2CVp6O-uVOo69q_lAkbEp_GgU7ZUHPs7a_';
        $this->service->sendPreferdTimeNotification($fcm);

    }
}
