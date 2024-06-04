<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Requests\AddNewExerciseTypeRequest;
use App\Models\Exercise;
use App\Models\ExerciseType;
use Illuminate\Http\Request;

class ExerciseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exerciseType=ExerciseType::with(['exercises'=>function($query){
            $query->with('focusAreas');
        }])->get();
        return $this->success($exerciseType);
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
    public function store(AddNewExerciseTypeRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = ImageController::store($data['image'], 'ExerciseType');
        }
        $data = ExerciseType::create($data);
        return $this->success($data, 'Exercise Type Added successfully', 200);
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $exerciseType=ExerciseType::with(['exercises'=>function($query){
            $query->with('focusAreas');
        }])->where('id',$id)->get();
        return $this->success($exerciseType);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExerciseType $exerciseType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExerciseType $exerciseType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $exerciseType = ExerciseType::find($id);

        if ($exerciseType) {
            $exercises = Exercise::where('exercise_type_id', request('id'))->get();
            foreach ($exercises as $exercise) {
                if ($exercise->private) {
                    $exercise->delete();
                }
            }
            $exerciseType->delete();
            return $this->success(null, 'ExerciseType Deleted Succesfully');
        }
        return $this->failed('Not found', 404);
    }
}
