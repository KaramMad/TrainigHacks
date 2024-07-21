<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Traits\ImageTrait;

class ProductController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data=Product::orderBy('id')->latest()->with('colors')->with('sizes')->get();
        return $this->success($data);
    }
    public function mostSales(){
        $data=Product::with(['colors','sizes'])->orderBy('sales_count','desc')->take(10)->get();
        return $this->success($data);
    }
    public function common(){
        $data=Product::with(['colors','sizes'])->orderBy('view_count','desc')->take(10)->get();
        return $this->success($data);
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
    public function store(StoreProductRequest $request)
    {
        $data=$request->validated();
        if($request->hasFile('image')){
            $data['image']=ImageTrait::store($data['image'],'Products');

        }
        $data=Product::create($data);
        $data->colors()->attach($request->color_id);
        $data->sizes()->attach($request->size_id);
        return $this->success($data,'Products added Succesfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product=$product->load(['colors','sizes']);
        return $this->success($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $des = Product::find($id);

        if ($des) {
            Product::where('id', '=', $id)->delete();

            return response()->json(['message' => 'Product deleted successfully'], 200);
        }
        return response()->json(['message' => 'not found'], 404);
    }
}
