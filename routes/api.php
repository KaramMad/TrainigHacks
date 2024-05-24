<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\api\V1\AdminController;
use App\Http\Controllers\api\V1\CoachAuthController;
use App\Http\Controllers\api\V1\UserController;
use App\Http\Controllers\api\V1\ArticlsController;
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

Route::group(['prefix'=>'coach',
    "middleware" => ["auth:coach",'scope:coach']
], function () {
    Route::get('logout',[CoachAuthController::class,'coachLogout']);
    Route::get('/{id}',[CoachAuthController::class,'show']);
    Route::post('createInfo', [CoachAuthController::class, 'store']);
    Route::post('updateCoachInfo',[CoachAuthController::class,'update']);
    Route::post('store', [MealController::class, 'store']);
    Route::post('show', [MealController::class, 'show']);
    Route::delete('destroy/{id}', [MealController::class, 'destroy']);
    Route::get('index', [MealController::class, 'index']);
});

//Admin Auth & Routes
Route::post('admin/login',[AdminController::class,'login']);
Route::group(['prefix'=>'admin',"middleware"=>["auth:admin",'scope:admin']],function(){
    Route::get('coach/allCoach',[CoachAuthController::class,'index']);
    Route::post('coach/addNewCoach',[AdminController::class,'createCoaches']);
    Route::post('articl/addNewArticls',[ArticlsController::class,'store']);
    Route::delete('articl/deleteArticl/{id}',[ArticlsController::class,'destroy']);
    Route::post('muscle/addNewMuscle',[MuscleController::class,'store']);
    Route::post('category/addNewCategory',[CategoryController::class,'store']);
    Route::delete('category/deleteCategory/{id}',[CategoryController::class,'destroy']);
    Route::delete('muscle/deleteMuscle/{id}',[MuscleController::class,'destroy']);
    Route::post('exercise/addNewExercises',[ExerciseController::class,'store']);
    Route::post('exercise/addNewExercises',[ExerciseController::class,'store']);
    Route::post('challenge/addNewChallenge',[ChallengeController::class,'store']);
    Route::delete('challenge/deleteChallenge/{challenge}',[ChallengeController::class,'destroy']);
    Route::post('store', [AdminMealController::class, 'store']);
    Route::get('latestMeals', [AdminMealController::class, 'latestMeals']);
    Route::post('show', [AdminMealController::class, 'show']);
    Route::get('getMealsWithNoneDiet', [AdminMealController::class, 'getMealsWithNoneDiet']);
    Route::delete('destroy\{id}', [AdminMealController::class, 'destroy']);
});

//Trainer Auth & Routes
Route::post('trainer/register',[AuthController::class,'trainerRegister']);
Route::post('/verfiy',[AuthController::class,'verficationRegister']);
Route::post('trainer/login',[AuthController::class,'trainerLogin']);
Route::post('trainer/forgotPasswor',[AuthController::class,'forgotPassword']);
Route::post('trainer/forgotPassword/verfiy',[AuthController::class,'verfiyForgotPassword']);
Route::post('trainer/password/reset',[AuthController::class,'passwordReset']);
Route::post('trainer/password/resend',[AuthController::class,'resendCode']);


Route::group(['prefix'=>'trainer',"middleware"=>["auth:user",'scope:user']],function(){
    Route::get('/logout',[AuthController::class,'trainerLogout']);
    Route::post('/info',[UserController::class,'trainerInfo']);
    Route::post('/password',[UserController::class,'changePassword']);
    Route::post('challenge/updateChallenge/{challenge}',[ChallengeController::class,'update']);
    Route::get('challenge/getAll',[ChallengeController::class,'index']);
    Route::post('exercise/gender',[ExerciseController::class,'filtering']);
    Route::get('exercise/all',[ExerciseController::class,'filtering']);
    Route::get('AddMealToFavoritesList\{id}', [FavoriteController::class, 'AddMealToFavoritesList']);
    Route::get('GetFavoritesList', [FavoriteController::class, 'GetFavoritesList']);
    Route::delete('deleteFromFavorite\{id}', [FavoriteController::class, 'deleteFromFavorite']);

});


//Public Routes
Route::get('allArticls',[ArticlsController::class,'index']);
Route::get('allArea',[MuscleController::class,'index']);
Route::get('allChallenge',[MuscleController::class,'index']);
Route::get('category/show',[CategoryController::class,'index']);
Route::post('category',[CategoryController::class,'show']);
