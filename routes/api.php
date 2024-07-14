<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\api\V1\AdminController;
use App\Http\Controllers\api\V1\CoachAuthController;
use App\Http\Controllers\api\V1\UserController;
use App\Http\Controllers\api\V1\ArticleController;
use App\Http\Controllers\api\V1\CategoryController;
use App\Http\Controllers\api\V1\ChallengeController;
use App\Http\Controllers\api\V1\ExerciseController;
use App\Http\Controllers\api\V1\MuscleController;
use App\Http\Controllers\api\V1\IngredientController;
use App\Models\Category;
use App\Models\Exercise;
use App\Http\Controllers\api\V1\CoachController;
use App\Http\Controllers\api\V1\MealController;
use App\Http\Controllers\api\V1\AdminMealController;
use App\Http\Controllers\api\V1\CommentController;
use App\Http\Controllers\api\V1\likeController;
use App\Http\Controllers\api\V1\PostController;
use App\Http\Controllers\api\V1\ExerciseTypeController;
use App\Http\Controllers\api\V1\FavoriteController;
use App\Http\Controllers\api\V1\ChatController;
use App\Models\ExerciseType;
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
    Route::post('store', [MealController::class, 'store']);
    Route::post('show', [MealController::class, 'show']);
    Route::delete('destroy/{id}', [MealController::class, 'destroy']);
    Route::get('index', [MealController::class, 'index']);
    Route::post('meal/store', [MealController::class, 'store']);
    Route::post('ingredient/store', [IngredientController::class, 'store']);
    Route::delete('ingredient/destroy\{id}', [IngredientController::class, 'destroy']);
    Route::post('ingredient/update\{id}', [IngredientController::class, 'update']);
    Route::post('post/addpost', [PostController::class, 'store']); // neeeewwwww
    Route::get('post/showAllPost', [PostController::class, 'index']); // neeeewwwww
    Route::get('post/deletePost/{id}', [PostController::class, 'destroy']); // neeeewwwww
    Route::post('comment/addcomment', [CommentController::class, 'store']); // neeeewwwww
    Route::get('comment/deletecomment/{id}', [CommentController::class, 'destroy']); // neeeewwwww
    Route::get('likePost/{id}', [likeController::class, 'likePost']); // neeeewwwww
    Route::get('likeComment/{id}', [likeController::class, 'likeComment']); // neeeewwwww
    Route::get('likePostsCount/{id}', [likeController::class, 'likePostsCount']); // neeeewwwww
    Route::get('likesCommentCount/{id}', [likeController::class, 'likesCommentCount']); // neeeewwwww
    Route::get('unlikePost/{id}', [likeController::class, 'unlikePost']); // neeeewwwww
    Route::get('unlikeComment/{id}', [likeController::class, 'unlikeComment']); // neeeewwwww
});

//Admin Auth & Routes
Route::post('admin/login', [AdminController::class, 'login']);
Route::group(['prefix' => 'admin', "middleware" => ["auth:admin", 'scope:admin']], function () {
    Route::get('articl/allArticls', [ArticleController::class, 'getAll']);
    Route::get('coach/allCoach', [CoachAuthController::class, 'index']);
    Route::post('coach/addNewCoach', [AdminController::class, 'createCoaches']);
    Route::post('articl/addNewArticls', [ArticleController::class, 'store']);
    Route::delete('articl/deleteArticl/{id}', [ArticleController::class, 'destroy']);
    Route::post('muscle/addNewMuscle', [MuscleController::class, 'store']);
    Route::delete('muscle/deleteMuscle/{id}', [MuscleController::class, 'destroy']);
    Route::post('category/addNewCategory', [CategoryController::class, 'store']);
    Route::delete('category/deleteCategory/{id}', [CategoryController::class, 'destroy']);
    Route::post('exercise/addNewExercises', [ExerciseController::class, 'store']);
    Route::post('exercise/addNewExercisesType', [ExerciseController::class, 'createExerciseType']);
    Route::post('exerciseType/addExerciseType', [ExerciseTypeController::class, 'store']);
    Route::delete('exerciseType/delExerciseType/{id}', [ExerciseTypeController::class, 'destroy']);
    Route::post('challenge/addNewChallenge', [ChallengeController::class, 'store']);
    Route::delete('challenge/deleteChallenge/{challenge}', [ChallengeController::class, 'destroy']);
    Route::post('meal/store', [AdminMealController::class, 'store']);
    Route::post('ingredient/store', [IngredientController::class, 'store']);
    Route::delete('ingredient/destroy/{id}', [IngredientController::class, 'destroy']);
    Route::post('ingredient/update/{id}', [IngredientController::class, 'update']);
    Route::delete('meal/destroy\{id}', [AdminMealController::class, 'destroy']);
    Route::post('post/addpost', [PostController::class, 'store']); // neeeewwwww
    Route::get('post/showAllPost', [PostController::class, 'index']); // neeeewwwww
    Route::get('post/deletePost/{id}', [PostController::class, 'destroy']); // neeeewwwww
    Route::post('comment/addcomment', [CommentController::class, 'store']); // neeeewwwww
    Route::get('comment/deletecomment/{id}', [CommentController::class, 'destroy']); // neeeewwwww
    Route::get('likePost/{id}', [likeController::class, 'likePost']); // neeeewwwww
    Route::get('likeComment/{id}', [likeController::class, 'likeComment']); // neeeewwwww
    Route::get('likePostsCount/{id}', [likeController::class, 'likePostsCount']); // neeeewwwww
    Route::get('likesCommentCount/{id}', [likeController::class, 'likesCommentCount']); // neeeewwwww
    Route::get('unlikePost/{id}', [likeController::class, 'unlikePost']); // neeeewwwww
    Route::get('unlikeComment/{id}', [likeController::class, 'unlikeComment']); // neeeewwwww
});

//Trainer Auth & Routes
Route::post('trainer/register', [AuthController::class, 'trainerRegister']);
Route::post('/verfiy', [AuthController::class, 'verficationRegister']);
Route::post('trainer/login', [AuthController::class, 'trainerLogin']);
Route::post('trainer/forgotPasswor', [AuthController::class, 'forgotPassword']);
Route::post('trainer/forgotPassword/verfiy', [AuthController::class, 'verfiyForgotPassword']);
Route::post('trainer/password/reset', [AuthController::class, 'passwordReset']);
Route::post('trainer/password/resend', [AuthController::class, 'resendCode']);


Route::group(['prefix' => 'trainer', "middleware" => ["auth:user", 'scope:user']], function () {
    Route::get('/logout', [AuthController::class, 'trainerLogout']);
    Route::post('/info', [UserController::class, 'trainerInfo']);
    Route::post('/password', [UserController::class, 'changePassword']);
    Route::post('challenge/updateChallenge/{challenge}', [ChallengeController::class, 'update']);
    Route::get('challenge/getAll', [ChallengeController::class, 'index']);
    Route::post('exercise/gender', [ExerciseController::class, 'filtering']);
    Route::get('exercise/getall', [ExerciseController::class, 'index']);
    Route::get('exercise/getall/{id}', [ExerciseController::class, 'show']);
    Route::get('meal/GetFavoritesList', [FavoriteController::class, 'GetFavoritesList']);
    Route::get('meal/AddMealToFavoritesList/{meal}', [FavoriteController::class, 'AddMealToFavoritesList']);
    Route::delete('meal/deleteFromFavorite/{meal}', [FavoriteController::class, 'deleteFromFavorite']);
    Route::get('meal/{meal}/isfav', [FavoriteController::class, 'isFavorite']);
    Route::get('meal/getAll', [AdminMealController::class, 'index']);
    Route::get('exerciseType/getType', [ExerciseTypeController::class, 'index']);
    Route::get('exerciseType/getType/{id}', [ExerciseTypeController::class, 'show']);
    Route::get('admin/meal/latestMeals', [AdminMealController::class, 'latestMeals']);
    Route::post('admin/meal/show', [AdminMealController::class, 'show']);
    Route::get('admin/meal/popular', [AdminMealController::class, 'popularMeal']);
    Route::post('admin/meal/search', [AdminMealController::class, 'search']);
    Route::get('admin/meal/getMealsWithNoneDiet', [AdminMealController::class, 'getMealsWithNoneDiet']);
    Route::post('admin/meal/showByCategory', [AdminMealController::class, 'showByCategory']);
    Route::post('coach/meal/show', [MealController::class, 'show']);
    Route::get('ingredient/index/{id}', [IngredientController::class, 'index']);
    Route::get('coach/allCoach', [CoachAuthController::class, 'index']);
    Route::post('post/addpost', [PostController::class, 'store']); // neeeewwwww
    Route::get('post/showAllPost', [PostController::class, 'index']); // neeeewwwww
    Route::get('post/deletePost/{id}', [PostController::class, 'destroy']); // neeeewwwww
    Route::post('comment/addcomment', [CommentController::class, 'store']); // neeeewwwww
    Route::get('comment/deletecomment/{id}', [CommentController::class, 'destroy']); // neeeewwwww
    Route::get('likePost/{id}', [likeController::class, 'likePost']); // neeeewwwww
    Route::get('likeComment/{id}', [likeController::class, 'likeComment']); // neeeewwwww
    Route::get('likePostsCount/{id}', [likeController::class, 'likePostsCount']); // neeeewwwww
    Route::get('likesCommentCount/{id}', [likeController::class, 'likesCommentCount']); // neeeewwwww
    Route::get('unlikePost/{id}', [likeController::class, 'unlikePost']); // neeeewwwww
    Route::get('unlikeComment/{id}', [likeController::class, 'unlikeComment']); // neeeewwwww
    Route::get('post/getUserPostsAndBio/{id}', [postController::class, 'getUserPostsAndBio']); // neeeewwwww



});


//Public Routes
Route::get('articl/allArticls', [ArticleController::class, 'index']);
Route::get('muscle/allArea', [MuscleController::class, 'index']);
Route::get('challenge/allChallenge', [MuscleController::class, 'index']);
Route::get('category/show', [CategoryController::class, 'index']);
Route::post('category', [CategoryController::class, 'show']);
