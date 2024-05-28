<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Requests\MuscleCategoryRequest;
use App\Models\Category;
use App\Providers\AppServiceProvider as AppSP;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::get();

        return AppSP::apiResponse("Category", $category, 'data', true, 200);
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
    public function store(MuscleCategoryRequest $request)
    {

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = ImageController::store($data['image'], "MuscleCategory");
        }
        if ($request->hasFile('men_image')) {
            $data['men_image'] = ImageController::store($data['men_image'], "MuscleCategory");
        }
        if ($request->hasFile('women_image')) {
            $data['women_image'] = ImageController::store($data['women_image'], "MuscleCategory");
        }

        $muscle = Category::create($data);

        return AppSP::apiResponse("Muscle Category Added Successfully", $muscle, 'Area', true, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category = Category::with(['exercises' => function ($query) {
            $query->where('gender', request('gender'));
        }])->where('id', '=', request('category_id'))->get();
        foreach ($category as $cat) {
            $cat->exercise_count = $cat->exercises->count();
            $cat->total_calories = $cat->exercises->sum('calories');
            $cat->total_time = $cat->exercises->sum('time');
        }
        if (request('gender') === 'female') {
            $category->makeHidden(['image', 'men_image', 'description']);
        }
        else
        {
            $category->makeHidden(['image', 'women_image', 'description']);

        }
        return response()->json($category, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $des = Category::find($id);

        if ($des) {
            Category::where('id', '=', $id)->delete();

            return response()->json(['message' => 'Category deleted successfully'], 200);
        }
        return response()->json(['message' => 'not found'], 404);
    }
}
