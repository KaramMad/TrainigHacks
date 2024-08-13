<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Bill;
use App\Models\Coach;
use App\Models\coachPlan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserPlanProgress;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Cast\Object_;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $coach = Coach::query()->first()->get();
        foreach ($coach as $coaches) {
            $coaches['subscribed'] = $user->subscribedCoaches()->where('coach_id', $coaches->id)->where('status', true)->exists();
            $coaches->save();
        }
        $coach = Coach::where('subscribed', true)->get();
        $coach = $coach->map(function ($rating) {
            $rating['rating'] = $rating->averageRating();
            return $rating;
        });
        return $this->success($coach);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        if ($user->subscribedCoaches()->where('coach_id', $data['coach_id'])->where('status', true)->exists()) {
            return $this->failed('You are already subscribed to this coach.');
        }
        if ($user->subscribedCoaches()->where('coach_id', $data['coach_id'])->where('status', false)->exists()) {
            $temp = Subscription::where('coach_id', $data['coach_id'])->where('status', false)->first();
            $temp->bill()->delete();
            $temp->delete();
        }
        $plan = coachPlan::where('coach_id', $data['coach_id'])->where('target', $user->target)->where('choose', $request->choose)->where('level', $user->level)->first();
        if(!$plan){
            return $this->failed('not available coach');
        }
        for ($i = 0; $i < 28; $i++) {
            $progress = new UserPlanProgress();
            $progress->plan_id = $plan->id;
            $progress->user_id = $user->id;
            $progress->status = $i === 0 ? 'unlocked' : 'locked';
            $progress->day=$i+1;
            $progress->created_at = Carbon::parse(now()->addDay($i))->format('d-m-Y');
            $progress->save();
        }
        $progress->save();
        $data = Subscription::create([
            'coach_id' => $data['coach_id'],
            'user_id' => $user->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
        ]);
        $bill = new Bill();
        $bill['total'] = $data->coach->price;
        $data->bill()->save($bill);
        return $this->success($data, 'Subscription created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function showCoachSubscribers()
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
