<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use App\Models\Meal;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

use App\Providers\AppServiceProvider as AppSP;

class FavoriteController extends Controller
{
    public function AddMealToFavoritesList(Meal $meal)
    {
        try {
            $user = User::find(Auth::id());
            $user->favorites()->syncWithoutDetaching($meal);
        } catch (QueryException) {
            return $this->failed('There is no meal with this id');
        }
        return response()->json(['Message' => 'Added Successfully'], 200);
    }
    public function AddProductToFavoritesList(Product $product)
    {
        try {
            $user = User::find(Auth::id());
            $user->favorite()->syncWithoutDetaching($product);
        } catch (QueryException) {
            return $this->failed('There is no prou$product with this id');
        }
        return response()->json(['Message' => 'Added Successfully'], 200);
    }

    public function GetFavoritesList()
    {

        $favs = auth()->user()->favorites()->get();
        $favs  = $favs->map(function ($data) {
            $data['isfavorite'] = $data->isfav();
            return $data;
        });
        $meal = $favs->map(function ($meal) {
            return [
                'ingredients' => $meal->ingredients
            ];
        });
        return response()->json([
            'message' => 'success',
            'meal' => $favs,
        ]);
    }
    public function GetProductFavoritesList()
    {

        $favs = auth()->user()->favorite()->get();
        $favs  = $favs->map(function ($data) {
            $data['isfavorite'] = $data->isfav();
            return $data;
        });
        return response()->json([
            'message' => 'success',
            'meal' => $favs,
        ]);
    }
    public function isFavorite(Meal $meal)
    {
        $isfav = $meal->isfav();
        return AppSP::apiResponse('checked succesfully', $isfav, 'isfavorite');
    }
    public function isProductFavorite(Product $product)
    {
        $isfav = $product->isfav();
        return AppSP::apiResponse('checked succesfully', $isfav, 'isfavorite');
    }
    public function deleteFromFavorite(Meal $meal)
    {
        $isfav = $meal->isfav();
        if ($isfav) {
            $user = User::find(Auth::id());
            $user->favorites()->detach($meal);
            return $this->success(null, 'removed from favorites');
        }
        return $this->failed('not in favorite list');
    }
    public function deleteProductFromFavorite(Product $product)
    {
        $isfav = $product->isfav();
        if ($isfav) {
            $user = User::find(Auth::id());
            $user->favorite()->detach($product);
            return $this->success(null, 'removed from favorites');
        }
        return $this->failed('not in favorite list');
    }
}
