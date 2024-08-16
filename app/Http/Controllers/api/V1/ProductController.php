<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Requests\SearchProductRequest;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\User;
use App\Traits\ImageTrait;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data=Product::orderBy('id')->latest()->with('colors')->with('sizes')->get();
        $data=$data->map(function($product){
            $product['isfavorite']=$product->isfav();
            return $product;
        });
        return $this->success($data);
    }
    public function adminIndex()
    {
        $data=Product::orderBy('id')->latest()->with('colors')->with('sizes')->get();
        return $this->success($data);
    }
    public function mostSales(){
        $data=Product::with(['colors','sizes'])->orderBy('sales_count','desc')->take(10)->get();
        $data=$data->map(function($product){
            $product['isfavorite']=$product->isfav();
            return $product;
        });
        return $this->success($data);
    }
    public function common(){
        $user=Auth::user();
        $viewedProducts = $user->favorite()->where('interactions', 'like')->pluck('products.id');
        $likedProducts = $user->favorite()->wherePivot('interactions', 'view')->pluck('products.id');
        $similarUsers = User::whereHas('favorite', function ($query) use ($viewedProducts,$likedProducts) {
            $query->whereIn('products.id', $viewedProducts)->orWhereIn('products.id',$likedProducts);
        })->pluck('users.id');
        $data = Product::whereHas('favoritedby', function ($query) use ($similarUsers) {
            $query->whereIn('users.id', $similarUsers);
        })->get();
//        $data=Product::with(['colors','sizes'])->orderBy('view_count','desc')->get();
//        $data=$data->map(function($product){
//            $product['isfavorite']=$product->isfav();
//            return $product;
//        });
        return $this->success($data);
    }

    public function search(SearchProductRequest $request){
        $data=$request->validated();
        $data=Product::latest()->filter(request(['search_text','category','home_Category']))->get();
        $data=$data->map(function($product){
            $product['isfavorite']=$product->isfav();
            return $product;
        });
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
        $data->category()->attach($request->category_id);
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
        $product['isfavorite']=$product->isfav();

        $user=Auth::user();
        $user->favorite()->syncWithoutDetaching([$product->id=>['interactions'=>'view']]);
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
