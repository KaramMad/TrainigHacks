<?php

namespace App\Http\Controllers\api\V1;

use App\Providers\AppServiceProvider as AppSP;
use App\Http\Requests\AddMuscleRequest;
use App\Models\Category;
use App\Models\Exercise;
use App\Models\Muscle;
use App\Models\Muscle_Level;
use Illuminate\Http\Request;

class MuscleController extends Controller
{




    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $muscles = Muscle::with(['exercises' => function ($query) {
            $query->where('level', request('level'));
        }])->with(['muscleLevels'=>function($query){
            $query->where('level',request('level'));
        }])->get();
        foreach ($muscles as $muscle) {
            $muscle->exercise_count = $muscle->exercises->count();
            $muscle->total_calories = $muscle->exercises->sum('calories');
            $muscle->total_time = $muscle->exercises->sum('time');
            $muscle->level=$muscle->muscleLevels->first()->level;
            $muscle->men_image=$muscle->muscleLevels->first()->men_image;
            $muscle->women_image=$muscle->muscleLevels->first()->women_image;
        }
        $muscles->makeHidden('muscleLevels');
        $muscles->makeHidden('exercises');
        return response()->json([
            'muscle_stats' =>$muscles,
        ]);
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
    public function store(AddMuscleRequest $request)
    {
        $data = $request->validated();
        $muscle = Muscle::create($data);
        if ($request->hasFile('men_image')) {
            $data['men_image'] = ImageController::store($data['men_image'], "Muscle");
        }
        if ($request->hasFile('women_image')) {
            $data['women_image'] = ImageController::store($data['women_image'], "Muscle");
        }
        $muscle->muscleLevel()->create([
            'level' => $data['level'],
            'men_image' => $data['men_image'],
            'women_image' => $data['women_image'],

        ]);
        return AppSP::apiResponse("Muscle Area Added Successfully", $muscle, 'Area', true, 200, $muscle->muscleLevel()->get());
    }

    /**
     * Display the specified resource.
     */
    public function show(Muscle $muscle)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Muscle $muscle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Muscle $muscle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $des = Muscle::find($id);

        if ($des) {
            Muscle::where('id', '=', $id)->delete();
            return response()->json('success');
        }
        return response()->json('not found');
    }
}
