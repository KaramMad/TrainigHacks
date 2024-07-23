<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Catproduct;
use App\Http\Requests\StoreCatproductRequest;
use App\Http\Requests\UpdateCatproductRequest;
use App\Traits\ImageTrait;

class CatproductController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreCatproductRequest $request)
    {
        $data=$request->validated();
        if($request->hasFile('image')){
            $data['image']=ImageTrait::store($data['image'],'ProductCategory');
        }
        $data=Catproduct::create($data);
        return $this->success($data,'category created succesfully');
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
        $status=$catproduct->delete();
        return $this->success($status,'deleted successfully');
    }
}
