<?php


namespace App\Http\Controllers\api\V1;

use App\Models\Exercise;
use Illuminate\Http\Request;
use App\Models\coachPlan;
use App\Http\Requests\StorecoachPlanRequest;
use App\Http\Requests\UpdatecoachPlanRequest;
use Illuminate\Support\Facades\Auth;

class CoachPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coachId = Auth::id();
        $data = coachPlan::where('coach_id', $coachId)->get();
        return $this->success($data);
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
    public function store(StorecoachPlanRequest $request)
    {
        $data = $request->validated();
        $coachId = Auth::id();
        $data['coach_id'] = $coachId;
        $data = coachPlan::create($data);
        return $this->success($data, 'created Plan Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $coachId = Auth::id();
        $data = Exercise::wherehas('coachPlans',function ($query) use ($coachId){
            $query->where('coach_id', $coachId)->where('coach_plans.id',request('coachPlanId'));
        })->get();
        return $this->success($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(coachPlan $coachPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coachId = Auth::id();
        $des = coachPlan::find($id);
        if ($des->coach_id != $coachId) {
            return response()->json(['Error' => 'You do not have permission to delete this Plan'], 422);
        }
        if ($des) {
            coachPlan::where('id', '=', $id)->delete();
            return response()->json('success');
        }
        return response()->json('not found');
    }
}
