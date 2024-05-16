<?php

namespace App\Http\Controllers\api\V1;
use App\Providers\AppServiceProvider as AppSP;
use App\Http\Requests\muscleRequest;
use App\Models\Muscle;
use Illuminate\Http\Request;

class MuscleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $muscle=Muscle::orderBy('level','asc')->get();
        return $muscle;
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
        $data=$request->validated();
        if($request->hasFile('image')){
            $data['image'] = ImageController::store($data['image'],"Muscle");
        }
        $muscle=Muscle::create($data);
        if(!$muscle){
            return AppSP::apiResponse("not found",null,'data',false,404);
        }
        return AppSP::apiResponse("Articl Added successfully",$muscle,'articl',true,200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Muscle $muscle)
    {
        //
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
        Muscle::where('id','=',$id)->delete();
        return response()->json('success');
    }
}
