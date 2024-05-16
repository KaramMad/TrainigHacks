<?php

namespace App\Http\Controllers\api\V1;
use App\Http\Requests\coachInfoRequest;
use App\Http\Requests\coachLoginRequest;
use Illuminate\Http\Request;
use App\Models\Coach;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Providers\AppServiceProvider as AppSP;
use Illuminate\Support\Facades\App;

class CoachAuthController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $coach=Coach::query()->first()->get();
         if(!$coach){
            return AppSP::apiResponse("There is no coaches has been found",null,'data',false,404);
        }
        return AppSP::apiResponse("Success",$coach,'coach',true,200);
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
    public function store(coachInfoRequest $request,Coach $cc)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = ImageController::update($data['image'], $cc['image'],"Profiles");
        }
        $data['password'] = Hash::make($request->password);
        $coach=Coach::find(Auth::id());
            $coach->update($data);

        return response()->json([

            'message' => 'Coach Info updated',
            'coach' => $coach
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $coach=Coach::query()->first()->where('id','=',$id)->get();
        if(!$coach){
            return AppSP::apiResponse("not found",null,'data',false,404);
        }
        return AppSP::apiResponse("Success",$coach,'coach',true,200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function coachLogin(coachLoginRequest $request)
    {
        $data=$request->validated();
        $verification = Coach::query()->firstWhere('phone_number', '=', $request->phone_number);

            if (Hash::check($data['password'],$verification['password'])) {
                return AppSP::apiResponse('Coach Login Successfully', $verification->createToken("API TOKEN", ['coach'])->accessToken, 'token', true);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => [
                        'error' => 'Phone Number & Password does not match with our record.'
                    ],
                ], 401);
            }

    }
    public function coachLogout()
    {
        Auth::guard('coach')->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Logged out',

        ], 200);
    }
}
