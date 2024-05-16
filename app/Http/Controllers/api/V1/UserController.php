<?php

namespace App\Http\Controllers\api\V1;


use App\Models\User;
use App\Models\TrainingDay;
use App\Http\Requests\UserInfoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Providers\AppServiceProvider as AppSP;

class UserController extends Controller
{
    public function trainerInfo(UserInfoRequest $request)
    {
        $validated = $request->validated();
        $user = User::find(Auth::id());
        $status=$user->update($validated);
        if ($request->hasFile('image')) {

            $picturePath = $request->file('image')->store('images');
            $user->picture = $picturePath;
        }

        $user->save();
        $user->trainingDays()->sync($request->training_days);
        $userTrainingDays = $user->trainingDays()->get();

        return response()->json([
            'trainer' =>[
                $user,
                "userTrainingDays" => $userTrainingDays,
            ],
            'message' =>"Trainer info added successfully",
        ]);
    }
}
