<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Catproduct;
use App\Http\Requests\StoreCatproductRequest;
use App\Http\Requests\UpdateCatproductRequest;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatproductController extends Controller
{
    use ImageTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Validator::make($request->all(), [
            'category' => 'required|exists:catproducts,category_name|string',
        ]);
        $data = Catproduct::with(['products'])->where('parent_id', null)->where('category_name', '=', $request->category)->first();
        $user = Auth::user();
        if (!$data) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $data->products->map(function ($product) use ($user) {
            $product['isfavorite'] = $user->favorite->contains($product->id);
            return $product;
        });
        return $this->success($data);
    }

    public function mainCat()
    {
        $data = Catproduct::where('parent_id', null)->select('id', 'category_name', 'image', 'description')->get();
        if (!$data) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return $this->success($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreCatproductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = ImageTrait::store($data['image'], 'ProductCategory');
        }
        $data = Catproduct::create($data);
        return $this->success($data, 'Subcategory created succesfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCatproductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = ImageTrait::store($data['image'], 'ProductCategory');
        }
        $data = Catproduct::create($data);
        return $this->success($data, 'category created succesfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Catproduct $catproduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Catproduct $catproduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCatproductRequest $request, Catproduct $catproduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Catproduct $catproduct)
    {
        ImageTrait::destroy($catproduct->image);
        $status = $catproduct->delete();
        return $this->success($status, 'deleted successfully');
    }
}
