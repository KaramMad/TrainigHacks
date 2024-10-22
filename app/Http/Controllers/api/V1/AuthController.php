<?php

namespace App\Http\Controllers\api\V1;

use App\Providers\AppServiceProvider as AppSP;
use App\Http\Requests\ResendCodeRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\SendCode;
use App\Models\TempUser;
use App\Models\VerfiyCode;


class AuthController extends Controller
{


    public function trainerRegister(Request $request)
    {
        $validateTrainer = Validator::make($request->all(), [
            'name' => 'required|max:55|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-_]).{6,}$/',
            'fcm_token' => 'required|string'
        ]);
        $data = $request->all();
        if ($validateTrainer->fails()) {
            return AppSP::apiResponse('validation Eror', $validateTrainer->errors(), 'errors', false, 422);
        }

        TempUser::query()->firstWhere('email', '=', $data['email'])?->delete();
        $code = mt_rand(100000, 999999);
        $data['code'] = $code;
        TempUser::query()->create($data);



        VerfiyCode::where('email', $request->email)->delete();

        $codeData = VerfiyCode::create([
            'code' => $code,
            'email' => $request->email
        ]);
        $mailed = Mail::to($request->email)->send(new SendCode($codeData->code));
        if ($mailed)
            return response(['message' => trans('check your email')], 200);
        else
            return response(['message' => trans('something went wrong')], 500);
    }

    public function verficationRegister(Request $request)
    {
        $validateCode =  Validator::make($request->all(), [
            'code' => 'required|string|exists:verfiy_codes',
        ]);

        if ($validateCode->fails()) {
            return AppSP::apiResponse('validation Eror', $validateCode->errors(), 'errors', false, 422);
        }
        // find the code
        $verification = VerfiyCode::firstWhere('code', $request->code);

        if ($verification) {
            $tempUser = TempUser::query()->firstWhere('code', '=', $request->code);
            $userData['name'] = $tempUser['name'];
            $userData['email'] = $tempUser['email'];
            $userData['fcm_token'] = $tempUser['fcm_token'];
            $userData['password'] = Hash::make($tempUser['password']);
            $tempUser->delete();
            $user = User::query()->create($userData);
            return AppSP::apiResponse('Trainer Created Successfully', $user->createToken("API TOKEN", ['user'])->accessToken, 'token', true, 201, $user);
        }


        // check if it does not expired: the time is one minute
        if ($verification->created_at > now()->addMinute()) {
            $verification->delete();
            return response(['message' => trans('code has been expired')], 422);
        }
    }
    public function resendCode(ResendCodeRequest $request)
    {
        $validated = $request->validated();
        $verification = TempUser::firstWhere('email', $request->email);
        if ($verification) {
            TempUser::query()->firstWhere('code')?->delete();
        }
        $code = mt_rand(100000, 999999);
        $data['code'] = $code;
        TempUser::query()->create($data);

        VerfiyCode::where('email', $request->email)->delete();

        $codeData = VerfiyCode::create([
            'code' => $code,
            'email' => $request->email
        ]);
        $mailed = Mail::to($request->email)->send(new SendCode($codeData->code));
        if ($mailed)
            return response(['message' => trans('check your email')], 200);
        else
            return response(['message' => trans('something went wrong')], 500);
    }

    public function trainerLogin(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|exists:users',
                'password' => 'required|min:8|',
                'fcm_token' => 'required|string',
            ]
        );
        $data = $request->all();
        if ($validateUser->fails()) {

            return AppSP::apiResponse('validaion error', $validateUser->errors(), 'errors', false, 422);
        }

        if (auth()->guard('web')->attempt($request->only(['email', 'password']))) {
            config(['auth.guards.user.provider' => 'auth.guards.user']);
            $user = User::query()->select('users.*')->find(auth()->guard('web')->user()['id']);
            $user->update(['fcm_token' => $request->fcm_token]);
            return AppSP::apiResponse('Trainer Login Successfully', $user->createToken("HomeWorkout", ['user'])->accessToken, 'token',true,200,$user);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        }
    }
    public function trainerLogout()
    {
        Auth::guard('user')->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Trainer Logged out',
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validateForgotPassword = Validator::make($request->only('email'), [
            'email' => 'required|email|exists:users'
        ]);
        if ($validateForgotPassword->fails()) {
            return AppSP::apiResponse('validation Eror', $validateForgotPassword->errors(), 'errors', false, 422);
        }
        VerfiyCode::where('email', $request->email)?->delete();
        $data['code'] = mt_rand(100000, 999999);
        $data['email'] = $request->email;
        // Create a new code
        $codeData = VerfiyCode::create($data);

        // Send email to user

        $input = Mail::to($request->email)->send(new SendCode($codeData->code));
        return response(['message' => trans('check your email')], 200);
    }

    public function verfiyForgotPassword(Request $request)
    {
        $validateCode = Validator::make($request->only('code'), [
            'code' => 'required|exists:verfiy_codes'
        ]);
        if ($validateCode->fails()) {
            return AppSP::apiResponse('validation Eror', $validateCode->errors(), 'errors', false, 422);
        }
        $verification = VerfiyCode::firstWhere('code', $request->code);
        if ($verification) {
            return AppSP::apiResponse('valid code', $request->code, 'code', true, 201);
        }
        return $this->failed('invalid code');
    }

    public function passwordReset(Request $request)
    {

        $validateCode = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'code' => 'required|string|exists:verfiy_codes',

        ]);


        if ($validateCode->fails()) {
            return AppSP::apiResponse('validation Eror', $validateCode->errors(), 'errors', false, 422);
        }
        $data = $request->all();

        $verification = VerfiyCode::firstWhere('code', $request->code);
        if ($verification) {
            $user = User::query()->firstWhere('email', '=', $data['email']);
            $user->update([
                'password' => Hash::make($data['password'])
            ]);
            $verification->delete();
            return AppSP::apiResponse('Password Reset Successfully', null, 'data', true, 201);
        }
    }
}
