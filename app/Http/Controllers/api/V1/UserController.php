<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Requests\changePasswordRequest;
use App\Models\User;
use App\Models\TrainingDay;
use App\Http\Requests\UserInfoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Providers\AppServiceProvider as AppSP;
use App\Traits\ImageTrait;
use GuzzleHttp\Psr7\Request;

class UserController extends Controller
{
    use ImageTrait;
    public function trainerInfo(UserInfoRequest $request,User $use)
    {
        $validated = $request->validated();
        $user = User::find(Auth::id());
        if ($request->hasFile('image')) {

           $validated['image']=ImageTrait::update($validated['image'],$use['image'],"Profiles");

        }

        $user->save();
        $user->update($validated);
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
    public function changePassword(changePasswordRequest $request){
        $data=$request->validated();
        $user=User::find(Auth::id());
        if(Hash::check($data['old_password'],$user['password'])){
            $user->update([
                'password'=>Hash::make($data['password']),
            ]);
        return response()->json([
            'message'=>'password has been updated successfully'
        ],200);
        }
        return response()->json([
            'message'=>'password does not match with our records'
        ],401);
    }



}
