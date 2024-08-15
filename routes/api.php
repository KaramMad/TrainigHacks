<?php

use App\Http\Controllers\api\V1\SubscriptionController;
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
use App\Http\Controllers\api\V1\NotificationController;
use App\Http\Controllers\api\V1\ReportController;
use App\Http\Controllers\api\V1\CoachController;
use App\Http\Controllers\api\V1\MealController;
use App\Http\Controllers\api\V1\AdminMealController;
use App\Http\Controllers\api\V1\CatproductController;
use App\Http\Controllers\api\V1\CommentController;
use App\Http\Controllers\api\V1\likeController;
use App\Http\Controllers\api\V1\PostController;
use App\Http\Controllers\api\V1\ExerciseTypeController;
use App\Http\Controllers\api\V1\FavoriteController;
use App\Http\Controllers\api\V1\ChatController;
use App\Http\Controllers\api\V1\CoachPlanController;
use App\Http\Controllers\api\V1\PosterController;
use App\Http\Controllers\api\V1\ProductController;
use App\Http\Controllers\api\V1\RatingController;
use App\Models\Favorite;
use \App\Http\Controllers\api\V1\FatoorahController;

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
Route::get('/callback', [FatoorahController::class, 'callBack']);
Route::get('/error', function () {
    return response()->json(['message' => 'something went wrong,try again later'], 422);
});
Route::post('/makeRefund', [FatoorahController::class, 'makePaymentRefunded']);

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
    Route::get('meal/index', [MealController::class, 'index']);
    Route::post('meal/store', [MealController::class, 'store']);
    Route::post('ingredient/store', [IngredientController::class, 'store']);
    Route::delete('ingredient/destroy\{id}', [IngredientController::class, 'destroy']);
    Route::post('ingredient/update\{id}', [IngredientController::class, 'update']);
    Route::get('ingredient/getAllIngredients', [IngredientController::class, 'getAllIngredients']); // *********************************
    Route::post('post/addpost', [PostController::class, 'store']);
    Route::get('post/showAllPost', [PostController::class, 'index']);
    Route::get('post/deletePost/{id}', [PostController::class, 'destroy']);
    Route::post('comment/addcomment', [CommentController::class, 'store']);
    Route::get('comment/deletecomment/{id}', [CommentController::class, 'destroy']);
    Route::get('comment/showComments/{id}', [CommentController::class, 'showComments']);
    Route::get('likePost/{id}', [likeController::class, 'likePost']);
    Route::get('likeComment/{id}', [likeController::class, 'likeComment']);
    Route::get('likePostsCount/{id}', [likeController::class, 'likePostsCount']);
    Route::get('likesCommentCount/{id}', [likeController::class, 'likesCommentCount']);
    Route::get('unlikePost/{id}', [likeController::class, 'unlikePost']);
    Route::get('unlikeComment/{id}', [likeController::class, 'unlikeComment']);
    Route::get('user/getAllUserSubscriptionWithCoach', [SubscriptionController::class, 'getAllUserSubscriptionWithCoach']);


    Route::group(['prefix' => 'plan'], function () {
        Route::get('/getAllPlan', [CoachPlanController::class, 'index']);
        Route::get('/planWithExercises', [CoachPlanController::class, 'show']);
        Route::post('/create', [CoachPlanController::class, 'store']);
        Route::delete('/deletePlan/{id}', [CoachPlanController::class, 'destroy']);
    });


    Route::group(['prefix' => 'exercise'], function () {
        Route::post('/create', [ExerciseController::class, 'createExercisePlan']);
        Route::delete('deletePlanExercise/{id}', [ExerciseController::class, 'destroy']);
    });
});

//Admin Auth & Routes
Route::post('admin/login', [AdminController::class, 'login']);
Route::group(['prefix' => 'admin', "middleware" => ["auth:admin", 'scope:admin']], function () {
    Route::get('articl/allArticls', [ArticleController::class, 'getAll']);
    Route::get('coach/allCoach', [CoachAuthController::class, 'adminIndex']);
    Route::post('coach/addNewCoach', [AdminController::class, 'createCoaches']);
    Route::post('articl/addNewArticls', [ArticleController::class, 'store']);
    Route::delete('articl/deleteArticl/{id}', [ArticleController::class, 'destroy']);
    Route::post('muscle/addNewMuscle', [MuscleController::class, 'store']);
    Route::delete('muscle/deleteMuscle/{id}', [MuscleController::class, 'destroy']);
    Route::post('category/addNewCategory', [CategoryController::class, 'store']);
    Route::delete('category/deleteCategory/{id}', [CategoryController::class, 'destroy']);
    Route::post('exercise/addNewExercises', [ExerciseController::class, 'store']);
    Route::post('exercise/addNewExercisesType', [ExerciseController::class, 'createExerciseType']);
    Route::delete('exercise/deleteExercise/{id}', [ExerciseController::class, 'destroy']);
    Route::get('exercise/getall', [ExerciseController::class, 'index']);
    Route::post('exerciseType/addExerciseType', [ExerciseTypeController::class, 'store']);
    Route::get('exerciseType/getAll', [ExerciseTypeController::class, 'index']);
    Route::delete('exerciseType/delExerciseType/{id}', [ExerciseTypeController::class, 'destroy']);
    Route::post('challenge/addNewChallenge', [ChallengeController::class, 'store']);
    Route::delete('challenge/deleteChallenge/{challenge}', [ChallengeController::class, 'destroy']);
    Route::get('challenge/getAll', [ChallengeController::class, 'adminIndex']);
    Route::post('meal/store', [AdminMealController::class, 'store']);
    Route::get('meal/getAll', [AdminMealController::class, 'getAllMeal']);
    Route::delete('meal/destroy/{id}', [AdminMealController::class, 'destroy']);
    Route::post('ingredient/store', [IngredientController::class, 'store']);
    Route::get('ingredient/getAll', [IngredientController::class, 'getAllIngredients']);
    Route::delete('ingredient/destroy/{id}', [IngredientController::class, 'destroy']);
    Route::post('ingredient/update/{id}', [IngredientController::class, 'update']);
    Route::get('ingredient/getAllIngredients', [IngredientController::class, 'getAllIngredients']);
    Route::delete('meal/destroy\{id}', [AdminMealController::class, 'destroy']);
    Route::post('post/addpost', [PostController::class, 'store']);
    Route::get('post/showAllPost', [PostController::class, 'index']);
    Route::get('post/deletePost/{id}', [PostController::class, 'destroy']);
    Route::post('comment/addcomment', [CommentController::class, 'store']);
    Route::get('comment/deletecomment/{id}', [CommentController::class, 'destroy']);
    Route::get('comment/showComments/{id}', [CommentController::class, 'showComments']);
    Route::get('likePost/{id}', [likeController::class, 'likePost']);
    Route::get('likeComment/{id}', [likeController::class, 'likeComment']);
    Route::get('likePostsCount/{id}', [likeController::class, 'likePostsCount']);
    Route::get('likesCommentCount/{id}', [likeController::class, 'likesCommentCount']);
    Route::get('unlikePost/{id}', [likeController::class, 'unlikePost']);
    Route::get('unlikeComment/{id}', [likeController::class, 'unlikeComment']);

    Route::group(['prefix' => 'products'], function () {
        Route::post('/create', [ProductController::class, 'store']);
        Route::get('/allProducts', [ProductController::class, 'adminIndex']);
        Route::delete('/DeleteProduct/{id}', [ProductController::class, 'destroy']);
        Route::post('/Poster/create', [PosterController::class, 'store']);
        Route::get('/order/index', [\App\Http\Controllers\api\V1\OrderController::class, 'index']);
        Route::get('/order/bill', [\App\Http\Controllers\api\V1\BillController::class, 'index']);
        Route::get('/order/status/sent/{order}', [\App\Http\Controllers\api\V1\OrderController::class, 'sent']);
        Route::get('/order/status/received/{order}', [\App\Http\Controllers\api\V1\OrderController::class, 'receive']);
        Route::post('/order/filterByStatus', [\App\Http\Controllers\api\V1\OrderController::class, 'filterByStatus']);
    });
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
    Route::get('meal/byId/{id}', [AdminMealController::class, 'getById']);
    Route::get('exerciseType/getType', [ExerciseTypeController::class, 'index']);
    Route::get('exerciseType/getType/{id}', [ExerciseTypeController::class, 'show']);
    Route::get('admin/meal/latestMeals', [AdminMealController::class, 'latestMeals']);
    Route::post('admin/meal/show', [AdminMealController::class, 'show']);
    Route::get('admin/meal/popular', [AdminMealController::class, 'popularMeal']);
    Route::post('admin/meal/search', [AdminMealController::class, 'search']);
    Route::get('admin/meal/getMealsWithNoneDiet', [AdminMealController::class, 'getMealsWithNoneDiet']);
    Route::post('admin/meal/showByCategory', [AdminMealController::class, 'showByCategory']);
    Route::get('ingredient/index/{id}', [IngredientController::class, 'index']);
    Route::get('coach/allCoach', [CoachAuthController::class, 'index']);
    Route::post('post/addpost', [PostController::class, 'store']);
    Route::get('post/showAllPost', [PostController::class, 'index']);
    Route::get('post/deletePost/{id}', [PostController::class, 'destroy']);
    Route::post('comment/addcomment', [CommentController::class, 'store']);
    Route::get('comment/deletecomment/{id}', [CommentController::class, 'destroy']);
    Route::get('comment/showComments/{id}', [CommentController::class, 'showComments']);
    Route::get('likePost/{id}', [likeController::class, 'likePost']);
    Route::get('likeComment/{id}', [likeController::class, 'likeComment']);
    Route::get('likePostsCount/{id}', [likeController::class, 'likePostsCount']);
    Route::get('likesCommentCount/{id}', [likeController::class, 'likesCommentCount']);
    Route::get('unlikePost/{id}', [likeController::class, 'unlikePost']);
    Route::get('unlikeComment/{id}', [likeController::class, 'unlikeComment']);
    Route::get('post/getUserPostsAndBio/{id}', [postController::class, 'getUserPostsAndBio']);
    Route::post('token', [NotificationController::class, 'updateToken']);
    Route::get('notifications', [NotificationController::class, 'getAllNotifications']);
    // Reports
    Route::post('report/creatReport', [ReportController::class, 'createOrUpdateReport']);
    Route::post('report/creatReportSteps', [ReportController::class, 'createOrUpdateStepsReport']);
    Route::get('report/getDailyReport', [ReportController::class, 'getDailyReport']);
    Route::get('report/getWeeklyReport', [ReportController::class, 'getWeeklyReport']);
    // Store-Section
    Route::group(['prefix' => 'products'], function () {
        Route::get('/allProducts', [ProductController::class, 'index']);
        Route::get('/getByid/{product}', [ProductController::class, 'show']);
        Route::get('/mostSales', [ProductController::class, 'mostSales']);
        Route::get('/common', [ProductController::class, 'common']);
        Route::post('/search', [ProductController::class, 'search']);
        Route::post('/catWithProduct', [CatproductController::class, 'index']);
        Route::get('/homeCategory', [CatproductController::class, 'mainCat']);
        Route::get('/GetFavoritesList', [FavoriteController::class, 'GetProductFavoritesList']);
        Route::get('/AddproductToFavoritesList/{product}', [FavoriteController::class, 'AddProductToFavoritesList']);
        Route::get('/{product}/isfav', [FavoriteController::class, 'isProductFavorite']);
        Route::delete('/deleteFromFavorite/{product}', [FavoriteController::class, 'deleteProductFromFavorite']);
        Route::get('/poster/all', [PosterController::class, 'index']);
        Route::post('/order/create', [\App\Http\Controllers\api\V1\OrderController::class, 'store']);
        Route::get('/order/index', [\App\Http\Controllers\api\V1\OrderController::class, 'index']);
        Route::delete('/order/cancel/{order}', [\App\Http\Controllers\api\V1\OrderController::class, 'destroy']);
        Route::post('/order/pay', [FatoorahController::class, 'payOrder']);
    });
    // Premium-section
    Route::prefix('subscription')->group(function () {
        Route::post('/create', [SubscriptionController::class, 'store']);
        Route::get('/index', [SubscriptionController::class, 'index']);
        Route::post('/pay', [FatoorahController::class, 'payOrder']);
        Route::post('progress/updateDay', [\App\Http\Controllers\api\V1\UserPlanProgressController::class, 'completeDay']);
        Route::post('progress/index', [\App\Http\Controllers\api\V1\UserPlanProgressController::class, 'index']);
    });
    Route::middleware(['subscription'])->group(function () {

        Route::prefix('rating')->group(function () {
            Route::post('/create/{coach_id}', [RatingController::class, 'store']);
        });
        Route::get('exercise/pickforYou', [ExerciseController::class, 'picksForYou']);
        Route::post('exercise/exercisePlan/{coach_id}', [ExerciseController::class, 'showPlanExerciseByDay']);
        Route::post('coach/meal/show/{coach_id}', [MealController::class, 'show']);
    });
});


//Public Routes
Route::get('articl/allArticls', [ArticleController::class, 'index']);
Route::get('muscle/allArea', [MuscleController::class, 'index']);
Route::get('challenge/allChallenge', [MuscleController::class, 'index']);
Route::get('category/show', [CategoryController::class, 'index']);
Route::post('category', [CategoryController::class, 'show']);

Route::post('testPreferdTime', [NotificationController::class, 'sendPreferdTime']);
Route::post('testTrainingDay', [NotificationController::class, 'sendTrainingDay']);


//Route::post('token', [NotificationController::class, 'sendPreferdTime'])->middleware('auth:user');
//Route::get('notifications', [NotificationController::class, 'getAllNotifications'])->middleware('auth:user');
