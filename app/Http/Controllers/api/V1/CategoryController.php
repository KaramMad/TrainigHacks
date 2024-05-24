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
        $muscle = Category::get();
        if (!$muscle) {
            return AppSP::apiResponse("not category found", null, 'data', false, 404);
        }
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
    public function store(MuscleCategoryRequest $request)
    {

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = ImageController::store($data['image'], "MuscleCategory");
        }
        if ($request->hasFile('main_image')) {
            $data['main_image'] = ImageController::store($data['main_image'], "MuscleCategory");
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
        $category->makeHidden(['image','main_image','description']);
        return $category;
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
