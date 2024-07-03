<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use App\Models\Coach;
use App\Models\Meal;
use App\Models\Favorite;
use App\Models\TrainingDay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\AdminMealRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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

    public function GetFavoritesList()
    {

        $favs = auth()->user()->favorites()->get();
        $favs  = $favs ->map(function ($data) {
            $data['isfavorite'] = $data->isfav();
            return $data;
        });
        $meal = $favs->map(function($meal) {
            return [
                'meal' => $meal,
                'ingredients' => $meal->ingredients
            ];
        });

        return AppSP::apiResponse('retrieved favorite products', $meal, 'meals');
    }
    public function isFavorite(Meal $meal)
    {
        $isfav = $meal->isfav();
        return AppSP::apiResponse('checked succesfully', $isfav, 'isfavorite');
    }
    public function deleteFromFavorite(Meal $meal)
    {
        $isfav=$meal->isfav();
        if($isfav){
            $user = User::find(Auth::id());
            $user->favorites()->detach($meal);
            return $this->success(null,'removed from favorites');
        }
        return $this->failed('not in favorite list');

    }
}
