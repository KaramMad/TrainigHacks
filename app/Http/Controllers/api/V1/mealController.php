<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use App\Models\Coach;
use App\Models\Meal;
use App\Models\TrainingDay;
use App\Http\Requests\MealRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Providers\AppServiceProvider as AppSP;

class MealController extends Controller // for coach
{
    //show all meals
    public function index()
    {
        $coachId = Auth::id();

        $meals = Meal::where('coach_id', $coachId)->get();

        return response()->json([
            'meals' => $meals
        ]);
    }

    public function store(MealRequest $request)//coach
    {
        $meal = $request->validated();
        $coachId = Auth::id();
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->move('meal_images', $imageName, 'storage/app/image');
            $meal['image'] = $imagePath;
        }
        $meal['coach_id'] = $coachId;
        $status = Meal::updateOrCreate($meal);
        return response()->json('Meals added successfully');
    }

    /* Display the specified resource.
    */
    public function show(Request $request)
    {
        $mealDay = Meal::where('day_id', $request->id)->where('target', '=', $request->target)->get();
        return response()->json([
            'meal' => $mealDay
        ]);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    /* Remove one meal .
     */
    public function destroy($mealId)
    {
        $coachId = Auth::id();
        //$coach = Meal::Where('coach_id',$coachId)->get();

        $meal = Meal::find($mealId);

        if (!$meal) {
            return response()->json(['Error' => 'The Meal does not exist'], 404);
        }

        if ($meal->coach_id != $coachId) {
            return response()->json(['Error' => 'You do not have permission to delete this meal'], 403);
        }

        $meal->delete();

        return response()->json(['Message' => 'Deleted Successfully'], 200);


    }
}
