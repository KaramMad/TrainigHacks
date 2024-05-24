<?php

namespace App\Http\Controllers\api\V1;
use Illuminate\Support\Facades\Auth;
use App\Providers\AppServiceProvider as AppSP;
use App\Http\Requests\AddExerciseRequest;
use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{

    public function filtering(Request $request){
        $exercise=Exercise::where('gender',request('gender'))->get();
        return $exercise;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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
        $exercise->focusAreas()->attach($request->focus_area);
        $exercise->categories()->attach($request->category);
        $exercise->muscles()->attach($request->muscle);
        $exercise=Exercise::with('focus_areas')->with('categories')->with('muscles')->get();
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
