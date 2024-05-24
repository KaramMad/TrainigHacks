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
    public function AddMealToFavoritesList($MealID)
    {
        $userID = Auth::id();
        $user = User::Where('id', $userID)->exists();
        if (!$user) {
            return response()->json(['Error' => 'user Is Not Found'], 404);
        }
        $IsMealExistsInFavoritesList = Favorite::Where('user_id', $userID)->Where('meal_id',$MealID)->exists();

        if ($IsMealExistsInFavoritesList) {
            return response()->json(['Message' => 'The Meal Is Already In Favorites List'], 404);
        }

        try {
            $ID = Favorite::create(['user_id' => $userID, 'meal_id' => $MealID])->id;
        } catch (QueryException $exception) {
            return response()->json(['Error' => $exception->errorInfo[2]], 400);
        }

        return response()->json(['Message' => 'Added Successfully'], 400);
    }

    public function GetFavoritesList()
    {
        $userID=Auth::id();
        $FavoritesList = Favorite::where('user_id', $userID)->get();
        foreach( $FavoritesList as $item)
        {
            $item['meal']=Meal::find($item->meal_id)->get();
        }
        return response()->json(['Data' => $FavoritesList], 400);
    }

    public function deleteFromFavorite($MealID)
    {
        $userID=Auth::id();
        $IsMealExistsInFavoritesList = Favorite::Where('user_id', $userID)
        ->Where('meal_id', $MealID)->exists();

        if (!$IsMealExistsInFavoritesList) {
            return response()->json(['Error' => 'The Meal Is Not In Favorites List'], 404);
        }

        $IsDeleted = Favorite::Where('user_id', $userID)->Where('meal_id', $MealID)->delete();
        return response()->json(['Message' => 'Deleted Successfully'], 404);
    }

}
