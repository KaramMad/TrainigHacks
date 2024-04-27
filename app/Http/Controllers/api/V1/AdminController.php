<?php

namespace App\Http\Controllers\api\V1;


use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Providers\AppServiceProvider as AppSP;

class AdminController extends Controller
{
    //

    public function login(Request $request)
    {
        try {
            $validateAdmin = Validator::make($request->all(), [
                'phone_number' => 'required|digits:10',
                'password' => 'required|min:8|',
            ]);
            if ($validateAdmin->fails()) {
                return AppSP::apiResponse('validation Eror', $validateAdmin->errors(), 'errors', false, 422);
            }

            if (auth()->guard('admin-web')->attempt($request->only(['phone_number', 'password']))) {
                config(['auth.guards.admin.provider' => 'auth.guards.admin']);
                $coach = Admin::query()->select('admins.*')->find(auth()->guard('admin-web')->user()['id']);
                return AppSP::apiResponse('Login Successfully', $coach->createToken("API TOKEN", ['coach'])->accessToken, 'token', true);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone Number & Password does not match with our record.',
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => null
            ], 500);
        }
    }
    public function createCoach()
    {
    }
}
