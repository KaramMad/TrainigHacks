<?php

namespace App\Http\Controllers\api\V1;
use App\Models\Admin;
use App\Models\Coach;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\AddNewCoachRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\VerfiyCode;
use UltraMsg\WhatsAppApi;
use App\Providers\AppServiceProvider as AppSP;


class AdminController extends Controller
{
    //

    public function login(AdminLoginRequest $request)
    {

            $validateAdmin = $request->validated();
            $admin= Admin::query()->firstWhere('phone_number', '=', $validateAdmin['phone_number']);
            if (Hash::check($validateAdmin['password'],$admin['password'])) {

                return AppSP::apiResponse('Login Successfully', $admin->createToken("API TOKEN", ['admin'])->accessToken, 'token', true);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => [
                        'error' => 'Phone Number & Password does not match with our record.'
                    ],
                ], 401);
            }

    }
    public function createCoaches(AddNewCoachRequest $request)
    {
        $validated = $request->validated();

        $code = rand(10000000, 99999999);
        $name = $validated['name'];
        $validated['password']=Hash::make($code);
        $coach = Coach::create($validated);
        require_once(base_path('vendor/autoload.php'));
        $ultramsg_token = 'wx141er7m12dejbc';
        $instance_id = 'instance85645';
        $client = new WhatsAppApi($ultramsg_token, $instance_id);
        $number = $number = "+963" . substr($validated['phone_number'], 1, 9);
        $to = $number;
        $body = "hey $name ,your account default password: $code
         don't forget to change it after first login.";
        $client->sendChatMessage($to, $body);

        if ($coach) {
            return response()->json([
                'status' => true,
                'message' => "coach Created successfully",
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'something get wrong',

            ], 500);
        }
    }
    public function allUsers(){
        $user =User::query()->count();
        $coach=Coach::query()->count();
        $products=Product::query()->count();
        return response()->json([
            'users'=>$user,
            'coaches'=>$coach,
            'products'=>$products,
        ],200);
    }
}
