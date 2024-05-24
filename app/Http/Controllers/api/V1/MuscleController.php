<?php

namespace App\Http\Controllers\api\V1;

use App\Providers\AppServiceProvider as AppSP;
use App\Http\Requests\muscleRequest;
use App\Models\Category;
use App\Models\Exercise;
use App\Models\Muscle;
use Illuminate\Http\Request;

class MuscleController extends Controller
{



   
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $muscles_beginner = Muscle::with(['exercises' => function ($query) {
            $query->where('level', request('muscle_area'));
        }])->get();
        // $muscles_intermediate = Muscle::with(['exercises' => function ($query) {
        //     $query->where('level', 'intermediate');
        // }])->get();
        // $muscles_advanced = Muscle::with(['exercises' => function ($query) {
        //     $query->where('level', 'advanced');
        // }])->get();

        foreach ($muscles_beginner as $muscle) {
            $muscle->exercise_count = $muscle->exercises->count();
            $muscle->total_calories = $muscle->exercises->sum('calories');
            $muscle->total_time = $muscle->exercises->sum('time');
            $muscle->level = request('muscle_area');
        }
        // foreach ($muscles_intermediate as $muscle) {
        //     $muscle->exercise_count = $muscle->exercises->count();
        //     $muscle->total_calories = $muscle->exercises->sum('calories');
        //     $muscle->total_time = $muscle->exercises->sum('time');
        //     $muscle->level = 'intermediate';
        // }
        // foreach ($muscles_advanced as $muscle) {
        //     $muscle->exercise_count = $muscle->exercises->count();
        //     $muscle->total_calories = $muscle->exercises->sum('calories');
        //     $muscle->total_time = $muscle->exercises->sum('time');
        //     $muscle->level = 'advanced';
        // }
         $muscles_beginner->makeHidden('exercises');
      //   $muscles_advanced->makeHidden('exercises');
        // $muscles_intermediate->makeHidden('exercises');
        return response()->json([
            'muscle_stats' => [
                $muscles_beginner,
           //     $muscles_intermediate,
             //   $muscles_advanced,
            ]
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
    public function store(muscleRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = ImageController::store($data['image'], "Muscle");
        }
        $muscle = Muscle::create($data);
        return AppSP::apiResponse("Muscle Area Added Successfully", $muscle, 'Area', true, 200);
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
