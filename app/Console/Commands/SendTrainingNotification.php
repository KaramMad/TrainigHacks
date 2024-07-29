<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use App\Services\NotificationService;


class SendTrainingNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:training-reminder';
    protected $description = 'Send reminder notification to users who have not opened the app on their training days';


    public function __construct(protected NotificationService $userService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::now()->format('l');
        $users = User::whereHas('trainingDays', function($query) use ($today) {
            $query->where('day', $today);
        })->get();

        foreach ($users as $user) {
            if ($user->last_login_at === null || $user->last_login_at->lt(Carbon::today())) {
                $this->userService->sendNotification(
                    $user->fcm_token,
                    [
                        'body' => 'Don\'t forget to do your training today!',
                        'title' => 'Training Reminder'
                    ]
                );
            }
        }

        return 0;
    }
}
