<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\api\V1\AdminController;
use App\Http\Controllers\api\V1\CoachAuthController;
use App\Http\Controllers\api\V1\IngredientController;
use App\Http\Controllers\api\V1\UserController;
use App\Http\Controllers\api\V1\ArticleController;
use App\Http\Controllers\api\V1\CategoryController;
use App\Http\Controllers\api\V1\ChallengeController;
use App\Http\Controllers\api\V1\ExerciseController;
use App\Http\Controllers\api\V1\MuscleController;
use App\Models\Category;
use App\Models\Exercise;
use App\Http\Controllers\api\V1\CoachController;
use App\Http\Controllers\api\V1\MealController;
use App\Http\Controllers\api\V1\AdminMealController;
use App\Http\Controllers\api\V1\FavoriteController;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Coach Auth & Routes
Route::post('coach/login', [CoachAuthController::class, 'coachLogin']);

Route::group([
    'prefix' => 'coach',
    "middleware" => ["auth:coach", 'scope:coach']
], function () {
    Route::get('logout', [CoachAuthController::class, 'coachLogout']);
    Route::get('/{id}', [CoachAuthController::class, 'show']);
    Route::post('createInfo', [CoachAuthController::class, 'store']);
    Route::post('updateCoachInfo', [CoachAuthController::class, 'update']);
    Route::post('meal/store', [MealController::class, 'store']);

    //Route::delete('meal/destroy/{id}', [MealController::class, 'destroy']);
    Route::post('ingredient/store', [IngredientController::class, 'store']);
    Route::delete('ingredient/destroy\{id}', [IngredientController::class, 'destroy']);
    Route::post('ingredient/update\{id}', [IngredientController::class, 'update']);
    //Route::get('index', [MealController::class, 'index']);
});

//Admin Auth & Routes
Route::post('admin/login', [AdminController::class, 'login']);
Route::group(['prefix' => 'admin', "middleware" => ["auth:admin", 'scope:admin']], function () {
    Route::get('coach/allCoach', [CoachAuthController::class, 'index']);
    Route::post('coach/addNewCoach', [AdminController::class, 'createCoaches']);
    Route::post('articl/addNewArticls', [ArticleController::class, 'store']);
    Route::delete('articl/deleteArticl/{id}', [ArticleController::class, 'destroy']);
    Route::post('muscle/addNewMuscle', [MuscleController::class, 'store']);
    Route::post('category/addNewCategory', [CategoryController::class, 'store']);
    Route::delete('category/deleteCategory/{id}', [CategoryController::class, 'destroy']);
    Route::delete('muscle/deleteMuscle/{id}', [MuscleController::class, 'destroy']);
    Route::post('exercise/addNewExercises', [ExerciseController::class, 'store']);
    Route::post('exercise/addNewExercises', [ExerciseController::class, 'store']);
    Route::post('challenge/addNewChallenge', [ChallengeController::class, 'store']);
    Route::delete('challenge/deleteChallenge/{challenge}', [ChallengeController::class, 'destroy']);
    Route::post('meal/store', [AdminMealController::class, 'store']);
    Route::post('ingredient/store', [IngredientController::class, 'store']);
    Route::delete('ingredient/destroy\{id}', [IngredientController::class, 'destroy']);
    Route::post('ingredient/update\{id}', [IngredientController::class, 'update']);
    Route::delete('meal/destroy\{id}', [AdminMealController::class, 'destroy']);
});
