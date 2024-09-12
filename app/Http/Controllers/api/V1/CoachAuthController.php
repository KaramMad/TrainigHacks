<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Requests\coachInfoRequest;
use App\Http\Requests\coachLoginRequest;
use Illuminate\Http\Request;
use App\Models\Coach;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Providers\AppServiceProvider as AppSP;
use App\Traits\ImageTrait;

class CoachAuthController extends Controller
{
    use ImageTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $coach = Coach::latest()->active($user)->get();
        if (!$coach) {
            return $this->success([], 'There is no suitable Coach ForYou');
        }


        foreach ($coach as $coaches) {
            $coaches['subscribed'] = $user->subscribedCoaches()->where('coach_id', $coaches->id)->where('status', true)->exists();
            $coaches->save();
        }
        $coach = $coach->map(function ($rating) {
            $rating['rating'] = $rating->averageRating();
            return $rating;
        });
        if (!$coach) {
            return AppSP::apiResponse("There is no coaches has been found", null, 'data', false, 404);
        }
        return AppSP::apiResponse("Success", $coach, 'coach', true, 200);
    }

    public function adminIndex()
    {

        $coach = Coach::get();
        $coach = $coach->map(function ($rating) {
            $rating['rating'] = $rating->averageRating();
            return $rating;
        });
        if (!$coach) {
            return AppSP::apiResponse("There is no coaches has been found", null, 'data', false, 404);
        }
        return AppSP::apiResponse("Success", $coach, 'coach', true, 200);
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
    public function store(coachInfoRequest $request, Coach $cc)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = ImageTrait::update($data['image'], $cc['image'], "Profiles");
        }
        $data['password'] = Hash::make($request->password);
        $coach = Coach::find(Auth::id());
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
        $coach = Coach::query()->first()->where('id', '=', $id)->get();
        if (!$coach) {
            return AppSP::apiResponse("not found", null, 'data', false, 404);
        }
        return AppSP::apiResponse("Success", $coach, 'coach', true, 200);
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
        $data = $request->validated();
        $verification = Coach::query()->firstWhere('phone_number', '=', $request->phone_number);

        if (Hash::check($data['password'], $verification['password'])) {
            $user = Coach::find($verification['id']);
            return AppSP::apiResponse('Coach Login Successfully', $verification->createToken("API TOKEN", ['coach'])->accessToken, 'token', true,200,$user);
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
            'message' => 'Coach Logged out',
        ], 200);
    }
}
