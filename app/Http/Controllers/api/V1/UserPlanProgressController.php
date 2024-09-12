<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Requests\storePlanProgressRequest;
use App\Models\coachPlan;
use App\Models\Subscription;
use App\Models\UserPlanProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPlanProgressController extends Controller
{
    public function completeDay(storePlanProgressRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $plan = coachPlan::active($user, $request)->first();
        if (!$plan) {
            return $this->success('Completed your plane renew your Subscription');

        }
        $data = UserPlanProgress::progress($user, $plan)->first();
        $data->created_at = now()->addDay();
        $nexDay = $data->created_at;
        $nextDay = UserPlanProgress::nextday($user, $plan, $nexDay)->first();
        if ($nextDay) {

            $nextDay->Update([
                'status' => 'unlocked',
            ]);
            $nextDay->save();
            return $this->success($nexDay, 'Day completed and next day unlocked!');
        } else {
            switch ($user->level) {
                case 'beginner':
                    $user->Update([
                        'level' => 'intermediate',
                    ]);
                    $user->save();
                    break;
                case 'intermediate':
                    $user->Update([
                        'level' => 'advanced',
                    ]);
                    $user->save();
                    break;
                default:
                    $user->Update([
                        'level' => 'advanced',
                    ]);
            }
            $subscription = Subscription::query()->where([
                ['user_id', $user->id],
                ['coach_id', $plan->coach_id],
            ])->first();
            $subscription->delete();
            return $this->success('Completed your plane renew your Subscription');
        }
    }

    public function index(Request $request)
    {
        $user=Auth::user();
        $plan = coachPlan::active($user, $request)->first();
        if (!$plan) {
            return $this->success('Completed your plane renew your Subscription');

        }
        $data = UserPlanProgress::query()->where('user_id',$user->id)->where('plan_id',$plan->id)->where('status', 'unlocked')->get();
        return $this->success($data);
    }
}
