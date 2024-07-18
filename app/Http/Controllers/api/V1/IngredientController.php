<?php

namespace App\Http\Controllers\api\V1;

use App\Providers\AppServiceProvider as AppSP;
use App\Models\Ingredient;
use App\Models\Meal;
use App\Http\Requests\IngredientRequest;
use App\Traits\ImageTrait;

class IngredientController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index($mealId)
    {
        $meal = Meal::with('ingredients')->find($mealId);
        if (!$meal) {
            return response()->json(['message' => 'Meal not found'], 404);
        }
        $ingredients = $meal->ingredients;
        return response()->json([
            'ingredients' => $ingredients
        ]);
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
    public function store(IngredientRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = ImageTrait::store($data['image'], 'Ingredients');
        }
        $ingredient = Ingredient::create($data);

        return AppSP::apiResponse("ingredient Added Successfully", $ingredient,  true, 200);
    }

    /**
     * Display the specified resource.
     */
    public function getAllIngredients()
    {
        $data = Ingredient::all();
        return response()->json([
            'ingredient' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IngredientRequest $request, string $id)
    {
        $ingredient = Ingredient::find($id);
        $data = $request->validated();
        $ingredient->update($data);

        return response()->json(['Message' => "Update Successfully"], 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ingredient = Ingredient::find($id);
        $ingredient->delete();
        return AppSP::apiResponse("ingredient Deleted Successfully", true, 200);
    }
}
