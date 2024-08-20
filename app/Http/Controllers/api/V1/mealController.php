<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use App\Models\Coach;
use App\Models\Meal;
use App\Models\Ingredient;
use App\Models\TrainingDay;
use App\Http\Requests\MealRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Providers\AppServiceProvider as AppSP;
use App\Traits\ImageTrait;

class MealController extends Controller // for coach
{
    use ImageTrait;
    //show all meals
    public function index()
    {
        $coachId = Auth::id();

        $meals = Meal::with('ingredients')->where('coach_id', $coachId)->get();

        return response()->json([
            'meals' => $meals
        ]);
    }

    public function store(MealRequest $request) //coach
    {
        $data = $request->validated();
        $coachId = Auth::id();
        $imagePath = null;
        if ($request->hasFile('image')) {
            $data['image'] = ImageTrait::store($data['image'], 'Meals');
        }
        $data['coach_id'] = $coachId;
        $status = Meal::Create($data);
        $status->ingredients()->sync($request->ingredients);
        $ingredientMeal = $status->ingredients()->get();
        return response()->json([
            'meal' => [
                $status,
                "ingredientMeal" => $ingredientMeal,
            ],
            'message' => 'Meal created successfully',
        ], 201);
        return response()->json('Meals added successfully');
    }

    /* Display the specified resource.
    */
    public function show(Request $request)
    {
        $data = $request->validate([
            'categoryName' => 'required|string|max:255',
            'day_id' => 'required|exists:training_days,id',

        ]);

        $user = auth()->user();
        $mealDay = Meal::with('ingredients')
            ->where('day_id', $data['day_id'])
            ->where('categoryName', $data['categoryName'])
            ->where('coach_id', request('coach_id'))

            ->get();

        return response()->json([
            'meal' => $mealDay,
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
        $meal = Meal::find($mealId);
        if (!$meal) {
            return response()->json(['Error' => 'The Meal does not exist'], 404);
        }

        if ($meal->coach_id != $coachId) {
            return response()->json(['Error' => 'You do not have permission to delete this meal'], 403);
        }
        ImageTrait::destroy($meal->image);
        $meal->delete();

        return response()->json(['Message' => 'Deleted Successfully'], 200);
    }
}
