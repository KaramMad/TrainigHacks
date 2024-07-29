<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Requests\PosterRequest;
use App\Models\Poster;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;

class PosterController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data=Poster::inRandomOrder()->take(3)->get();
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
    public function store(PosterRequest $request)
    {
        $data=$request->validated();
        if($request->hasFile('image')){
            $data['image']=ImageTrait::store($data['image'],'ProductPosters');
        }
        return $this->success($data);

    }

    /**
     * Display the specified resource.
     */
    public function show(Poster $poster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Poster $poster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Poster $poster)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poster $poster)
    {
        //
    }
}
