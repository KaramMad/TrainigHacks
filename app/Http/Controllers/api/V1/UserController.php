<?php

namespace App\Http\Controllers\api\v1;


use App\Models\User;
use App\Models\TrainingDay;
use App\Models\UserTrainingDay;
use App\Http\Requests\UserInfo;
use App\Http\Requests\AddDay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Providers\AppServiceProvider as AppSP;

class UserController extends Controller
{
    public function trainerInfo(UserInfo $request)
    {
        $validated = $request->validated();
        $userId = Auth::id();

        User::where('id', $userId)->update([
            'gender' => $request->gender,
            'target' => $request->target,
            'weight' => $request->weight,
            'tall' => $request->tall,
            'preferred_time' => $request->preferred_time,
            'focus_area' => $request->focus_area,
            'diseases' => $request->diseases,
        ]);

        $user = User::find($userId);
        $user->trainingDays()->sync($request->training_days);


        $userTrainingDays = $user->trainingDays()->get();

        return response()->json([
            'trainer'=>$user,
            'message' => "Trainer info added successfully",
            "userTrainingDays" => $userTrainingDays,
        ]);
    }

    public function addDay(AddDay $request)
    {
        $validated = $request->validated();
        try {
            $day = TrainingDay::create([
                'day' => $request->day,
            ]);
            return response()->json(['message' => 'Day added successfully'], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Failed to add day', 'error' => $e->getMessage()], 500);
        }
    }
}
