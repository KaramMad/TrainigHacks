<?php

namespace App\Http\Controllers\api\V1;

use App\Providers\AppServiceProvider as AppSP;
use App\Http\Requests\ArticlRequest;
use App\Models\Article as Articl;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articl = Articl::inRandomOrder()->limit(4)->get();
        return $articl;
    }
    public function  getAll() {
        $articl = Articl::get();
        return $articl;
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
    public function store(ArticlRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('Image')) {
            $data['Image'] = ImageController::store($data['Image'], "Articls");
        }
        $articl = Articl::create($data);
        if (!$articl) {
            return AppSP::apiResponse("not found", null, 'data', false, 404);
        }
        return AppSP::apiResponse("Articl Added successfully", $articl, 'articl', true, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Articl $articls)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Articl $articls)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Articl $articls)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $articl = Articl::find($id);

        if ($articl) {
            $articl->delete();
            return response()->json(['message' => 'success'], 200);
        }
        else{
            return response()->json(['message' => 'Not Found'], 404);

        }
    }
}
