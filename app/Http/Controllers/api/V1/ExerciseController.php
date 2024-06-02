<?php

namespace App\Http\Controllers\api\V1;
use Illuminate\Support\Facades\Auth;
use App\Providers\AppServiceProvider as AppSP;
use App\Http\Requests\AddExerciseRequest;
use App\Models\Exercise;
use App\Models\Muscle;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{

    public function filtering(Request $request){
        $exercise=Muscle::with(['exercises'=>function($query){
            $query->where('gender','=',request('gender'));
            $query->where('level','=',request('level'));
            $query->with('focusAreas');
        }])->where('id','=',$request->muscle_id)->get();
        foreach ($exercise as $muscle) {
            $muscle->exercise_count = $muscle->exercises->count();
            $muscle->total_calories = $muscle->exercises->sum('calories');
            $muscle->total_time = $muscle->exercises->sum('time');
        }

        return AppSP::apiResponse("success",$exercise,'exercise',true,200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exercise=Exercise::with('focusAreas')->orderBy('level')->orderBy('gender')->get();
        return $exercise;
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
    public function store(AddExerciseRequest $request)
    {
        $data=$request->validated();
        $exercise=Exercise::find(Auth::id());
        if($request->hasFile('gif')){
            $data['image']=ImageController::store($data['image'],'Exercises');
        }
        $exercise=Exercise::create($data);
        $exercise->categories()->attach($request->category);
        $exercise->muscles()->attach($request->muscle);
        $exercise->focusAreas()->attach($request->focus_area);
        $exercise=Exercise::with('focusAreas')->with('categories')->with('muscles')->get();
        return response()->json([
            'exercise'=>$exercise,
            'message' =>"Exercises added successfully",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Exercise $exercise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exercise $exercise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exercise $exercise)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exercise $exercise)
    {
        //
    }
}
