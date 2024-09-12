<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Requests\AddChallengeRequest;
use App\Http\Requests\UpdateChallengeRequest;
use App\Models\Challenge;
use App\Traits\ImageTrait;

class ChallengeController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $challenge = Challenge::limit(4)->get();
        return $challenge;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function adminIndex()
    {
        $challenge = Challenge::get();
        return $this->success($challenge) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddChallengeRequest $request)
    {

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = ImageTrait::store($data['image'], "Challenge");
        }
        if ($request->hasFile('gif')) {
            $data['gif'] = ImageTrait::store($data['gif'], "Challenge");
        }
        if($request->hasFile('secondry_image')){
            $data['secondry_image'] = ImageTrait::store($data['secondry_image'], "Challenge");

        }
        $challenge=Challenge::create($data);
        return response()->json([
            'message' => 'added successfully',
            'data' => $challenge,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Challenge $challenge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Challenge $challenge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChallengeRequest $request, Challenge $challenge)
    {
        $data = $request->validated();
        $challenge->update($data);
        return response()->json([
            'message' => 'updated successfully',
            'data' => $challenge,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Challenge $challenge)
    {
        //
    }
}
