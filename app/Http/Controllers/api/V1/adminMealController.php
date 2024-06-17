<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use App\Models\Coach;
use App\Models\Meal;
use App\Models\MealType;
use App\Models\Ingredient;
use App\Models\TrainingDay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\AdminMealRequest;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Providers\AppServiceProvider as AppSP;

class AdminMealController extends Controller
{
    /* Display a listing of the resource.
     */
    public function index()
    {
        $data = Meal::with('ingredients')->latest()->whereNull('coach_id')->get();
        $data = $data->map(function ($meal) {
            $meal['isfavorite'] = $meal->isfav();
            return $meal;
        });
        return $this->success($data);
    }

    // just for now
    public function popularMeal()
    {
        $data = Meal::with('ingredients')->whereNull('coach_id')->take(5)->orderBy('name')->get();
        $data = $data->map(function ($meal) {
            $meal['isfavorite'] = $meal->isfav();
            return $meal;
        });
        return $this->success($data);
    }


    public function search(SearchRequest $request)
    {
        $data = $request->validated();
        $data = Meal::latest()->filter(request(['search_text']))->offset($request->start)->limit($request->limit)->get();
        return $this->success($data);
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
        $latestMeals = Meal::with('ingredients')->orderBy('created_at', 'desc')->take(5)->whereNull('coach_id')->get();
        return response()->json($latestMeals);
    }
    public function show(Request $request)
    {
        $meal = Meal::with('ingredients')->where('type', $request->type)->where('categoryName',$request->categoryName)
            ->whereNull('coach_id')->get();
        return $this->success($meal);
    }
    public function showByCategory(Request $request)
    {
        $meal = Meal::with('ingredients')->where('categoryName',$request->categoryName)->whereNull('coach_id')->get();
        return $this->success($meal);
    }

    public function getMealsWithNoneDiet()
    {
        $meals = Meal::with('ingredients')->where('type', 'none')->whereNull('coach_id')->get();
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
