<?php

namespace App\Http\Controllers\api\V1;


use Illuminate\Support\Facades\Auth;
use App\Providers\AppServiceProvider as AppSP;
use App\Http\Requests\AddExerciseRequest;
use App\Http\Requests\exercisePlanRequest;
use App\Http\Requests\ExercisesTypeRequest;
use App\Http\Requests\StoreExercisePlanReques;
use App\Models\coachPlan;
use App\Models\Exercise;
use App\Models\Muscle;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    use ImageTrait;

    public function filtering(Request $request)
    {
        $exercise = Muscle::with(['exercises' => function ($query) {
            $query->where('gender', '=', request('gender'));
            $query->where('level', '=', request('level'));
            $query->with('focusAreas');
            $query->where('private', '0');
        }])->where('id', '=', $request->muscle_id)->get();
        $exercise = $exercise->map(function ($muscle) {
            $muscle['exercise_count'] = $muscle->exercises->count();
            $muscle['total_calories'] = $muscle->exercises->sum('calories');
            $muscle['total_time'] = $muscle->exercises->sum('time');
            return $muscle;
        });
        return AppSP::apiResponse("success", $exercise, 'exercise', true, 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exercise = Exercise::with('focusAreas')->orderBy('level')->orderBy('gender')->get();
        return $exercise;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function picksForYou()
    {
        $user = auth()->user();
        $exercise = Exercise::with('focusAreas')->where('diseases', $user->diseases)->limit(10)->get();
        return $this->success($exercise);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddExerciseRequest $request)
    {
        $data = $request->validated();
        $exercise = Exercise::find(Auth::id());
        if ($request->hasFile('gif')) {
            $data['gif'] = ImageTrait::store($data['gif'], 'Exercises');
        }
        $exercise = Exercise::create($data);
        $exercise->categories()->attach($request->category);
        $exercise->muscles()->attach($request->muscle);
        $exercise->focusAreas()->attach($request->focus_area);
        return $this->success($data, 'create exercise successfully');
    }

    public function createExercisePlan(StoreExercisePlanReques $request)
    {
        $data = $request->validated();
        $exercise = Exercise::find(Auth::id());
        if ($request->hasFile('gif')) {
            $data['gif'] = ImageTrait::store($data['gif'], 'Exercises');
        }
        $coachId = Auth::id();
        $plan = coachPlan::where('id', $request->plan_id)
            ->where('coach_id', $coachId)
            ->first();

        if (!$plan) {
            return response()->json([
                'message' => 'The specified plan does not belong to you.'
            ], 403);
        }

        $data['target'] = $plan->target;
        $data['level'] = $plan->level;
        $data['choose'] = $plan->choose;
        $exercise = Exercise::create($data);
        $exercise->muscles()->attach($request->muscle);
        $exercise->focusAreas()->attach($request->focus_area);
        $exercise->days()->attach($request->day_id);
        $exercise->coachPlans()->attach($request->plan_id);
        return response()->json([
            'message' => "Exercises added to Plan successfully",
            'exercise' => $exercise,
        ]);
    }


    public function createExerciseType(ExercisesTypeRequest $request)
    {
        $data = $request->validated();
        $exercise = Exercise::find(Auth::id());
        if ($request->hasFile('gif')) {
            $data['gif'] = ImageTrait::store($data['gif'], 'Exercises');
        }
        $exercise = Exercise::create($data);
        $exercise->categories()->attach($request->category);
        $exercise->muscles()->attach($request->muscle);
        $exercise->focusAreas()->attach($request->focus_area);
        $exercise = Exercise::with('focusAreas')->with('categories')->with('muscles')->get();
        return $this->success($exercise, 'exercise added successfully to ExerciseType');
    }

    /**
     * Display the specified resource.
     */
    public function showPlanExerciseByDay(exercisePlanRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        $data = Exercise::wherehas('coachPlans', function ($query) use ($user) {
            $query->where('coach_id', request('coach_id'))->where('target',$user->target)->where('level',$user->level)->where('choose',request('choose'));
        })->wherehas('days', function ($query) {
            $query->where('training_days.id', request('day_id'));
        })->with('focusAreas')->get();
        return $this->success($data);
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
    public function destroy($id)
    {
        $des = Exercise::find($id);

        if ($des) {
            Exercise::where('id', '=', $id)->delete();
            return response()->json('success');
        }
        return $this->failed('not found', 404);
    }
}
