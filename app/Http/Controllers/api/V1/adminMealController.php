<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use App\Models\Coach;
use App\Models\Meal;
use App\Models\TrainingDay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\AdminMealRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Providers\AppServiceProvider as AppSP;

class AdminMealController extends Controller
{
    /* Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /* Store a newly created resource in storage.
     */
    public function store(AdminMealRequest $request)
    {
        $validatedData= $request->validated();
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->move('meal_images', $imageName, 'storage/app/image');
            $meal['image'] = $imagePath;
        }
        $meal = Meal::create($validatedData);
        return response()->json([
            'message' => 'Meal created successfully',
            'meal' => $meal], 201);
    }

    public function latestMeals()
    {
        $latestMeals = Meal::orderBy('created_at', 'desc')->take(5)->get();
        return response()->json($latestMeals);
    }
    public function show(Request $request)
    {

        $meal = Meal::where('diet', $request->diet)->where('diet', '=', $request->diet)->get();
        return response()->json([
            'meal' => $meal
        ]);
    }
    public function getMealsWithNoneDiet()
    {
        $meals = Meal::where('diet', 'none')->get();
        return response()->json($meals);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($mealId)
    {
        $IsMeal = Meal::Where('id', $mealId);
        if (!$IsMeal) {
            return response()->json(['Error' => 'The Meal Is Not In exist'], 404);
        }

        $IsDeleted = Meal::Where('id', $mealId)->delete();
        return response()->json(['Message' => 'Deleted Successfully'], 200);
    }
}
