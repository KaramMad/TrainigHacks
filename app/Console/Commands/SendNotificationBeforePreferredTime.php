<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\NotificationService;
use Carbon\Carbon;

class SendNotificationBeforePreferredTime extends Command
{
    protected $signature = 'send:notification-before-preferred-time';
    protected $description = 'Send notification to users 15 minutes before their preferred time';
    protected $userService;
    public function __construct(NotificationService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $preferredTime = Carbon::parse($user->preferred_time);
            $notificationTime = $preferredTime->subMinutes(15);
            $currentTime = Carbon::now();
            if ($currentTime->greaterThanOrEqualTo($notificationTime) && $currentTime->lessThan($preferredTime)) {
                $this->userService->sendPreferdTimeNotification(
                    $user->fcm_token,
                    [
                        'body' => 'Your preferred time is in 15 minutes!',
                        'title' => 'Reminder'
                    ]
                );

            }
        }
        return 0;
    }
}
