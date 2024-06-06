<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use App\Models\Coach;
use App\Models\Meal;
use App\Models\Ingredient;
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
        $validatedData = $request->validated();
        if ($request->hasFile('image')) {
            $validatedData['image'] = ImageController::store($validatedData['image'], 'Meals');
        }

        $diseases = ['heart', 'knee', 'breath', 'blood pressure', 'diabetes'];
        $userDiseases = auth()->user()->diseases ?? [];
        $warnings = [];
        foreach ($diseases as $disease) {
            if (in_array($disease, $userDiseases)) {
                if ($validatedData['calories'] > 700) {
                    $warnings[] = 'High in calories, not suitable for heart conditions';
                }

                if ($validatedData['sugar'] > 20) {
                    $warnings[] = 'High in sugar, not suitable for diabetes';
                }

                if ($validatedData['salt'] > 2) {
                    $warnings[] = 'High in salt, not suitable for high blood pressure';
                }
                break;
            }
        }

        // $userTarget = auth()->user()->target;
        // if ($userTarget !== $validatedData['target']) {
        //     $warnings[] = 'Meal does not align with user target';
        // }

        $validatedData['warning'] = implode(' ', $warnings);
        $meal = Meal::create($validatedData);
        $meal->ingredients()->sync($request->ingredients);
        $ingredientMeal = $meal->ingredients()->get();
        return response()->json([
            'meal' => [
                $meal,
                "ingredientMeal" => $ingredientMeal,
            ],
            'message' => 'Meal created successfully',
        ], 201);
    }

    public function latestMeals()
    {
        $latestMeals = Meal::orderBy('created_at', 'desc')->take(5)->whereNull('coach_id')->get();
        return response()->json($latestMeals);
    }
    public function show(Request $request)
    {
        $meal = Meal::where('diet', $request->diet)->where('diet', '=', $request->diet)
            ->whereNull('coach_id')->get();
        return response()->json([
            'meal' => $meal
        ]);
    }
    public function getMealsWithNoneDiet()
    {
        $meals = Meal::where('diet', 'none')->whereNull('coach_id')->get();
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
