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
use App\Http\Controllers\api\V1\MealController;
use App\Http\Controllers\api\V1\AdminMealController;
use App\Http\Controllers\api\V1\CatproductController;
use App\Http\Controllers\api\V1\CommentController;
use App\Http\Controllers\api\V1\likeController;
use App\Http\Controllers\api\V1\PostController;
use App\Http\Controllers\api\V1\ExerciseTypeController;
use App\Http\Controllers\api\V1\FavoriteController;
use App\Http\Controllers\api\V1\CoachPlanController;
use App\Http\Controllers\api\V1\PosterController;
use App\Http\Controllers\api\V1\ProductController;
use App\Http\Controllers\api\V1\RatingController;
use App\Http\Controllers\api\V1\FatoorahController;

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

// Route::middleware(['AcceptJsonResponseMidlleware'])->group(function () {

// });
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
    //1-Meal Section
    Route::post('store', [MealController::class, 'store']);
    Route::post('show', [MealController::class, 'show']);
    Route::delete('meal/destroy/{id}', [MealController::class, 'destroy']);
    Route::get('meal/index', [MealController::class, 'index']);
    Route::post('meal/store', [MealController::class, 'store']);
    Route::post('ingredient/store', [IngredientController::class, 'store']);
    Route::delete('ingredient/destroy/{id}', [IngredientController::class, 'destroy']);
    Route::post('ingredient/update/{id}', [IngredientController::class, 'update']);
    Route::get('ingredient/getAllIngredients', [IngredientController::class, 'getAllIngredients']);

    //2-Media Section
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
    //3-Dashboard Section
    Route::get('user/getAllUserSubscriptionWithCoach', [SubscriptionController::class, 'getAllUserSubscriptionWithCoach']);
    Route::get('user/totalSale', [ReportController::class, 'totalSale']);


    //Coach Plans Details
    Route::group(['prefix' => 'plan'], function () {
        Route::get('subscriptionEnds/{id}', [SubscriptionController::class, 'subscriptionEnds']);
        Route::get('/getAllPlan', [CoachPlanController::class, 'index']);
        Route::get('/planWithExercises', [CoachPlanController::class, 'show']);
        Route::post('/create', [CoachPlanController::class, 'store']);
        Route::delete('/deletePlan/{id}', [CoachPlanController::class, 'destroy']);
    });

    //Coach Plan Exercise
    Route::group(['prefix' => 'exercise'], function () {
        Route::post('/create', [ExerciseController::class, 'createExercisePlan']);
        Route::delete('deletePlanExercise/{id}', [ExerciseController::class, 'destroy']);
    });
});

//Admin Auth & Routes
Route::post('admin/login', [AdminController::class, 'login']);
Route::group(['prefix' => 'admin', "middleware" => ["auth:admin", 'scope:admin']], function () {

    //1-Articls Section
    Route::group(['prefix'=>'articl'],function(){

        Route::get('/allArticls', [ArticleController::class, 'getAll']);
        Route::post('/addNewArticls', [ArticleController::class, 'store']);
        Route::delete('deleteArticl/{id}', [ArticleController::class, 'destroy']);
    });
    //2-Coach Section

    Route::get('coach/allCoach', [CoachAuthController::class, 'adminIndex']);
    Route::post('coach/addNewCoach', [AdminController::class, 'createCoaches']);
    //3-Muscle Section
    Route::group(['prefix'=>'muscle'],function(){

        Route::post('addNewMuscle', [MuscleController::class, 'store']);
        Route::delete('deleteMuscle/{id}', [MuscleController::class, 'destroy']);
    });

    //4-ExerciseCategory Section
    Route::group(['prefix'=>'category'],function(){

        Route::post('/addNewCategory', [CategoryController::class, 'store']);
        Route::delete('/deleteCategory/{id}', [CategoryController::class, 'destroy']);
    });

    //5-Exercise Section
    Route::group(['prefix'=>'exercise'],function(){

        Route::post('/addNewExercises', [ExerciseController::class, 'store']);
        Route::post('/addNewExercisesType', [ExerciseController::class, 'createExerciseType']);
        Route::delete('/deleteExercise/{id}', [ExerciseController::class, 'destroy']);
        Route::get('/getall', [ExerciseController::class, 'index']);
    });

    //6-ExerciseType Section
    Route::group(['prefix'=>'exerciseType'],function(){

        Route::post('/addExerciseType', [ExerciseTypeController::class, 'store']);
        Route::get('/getAll', [ExerciseTypeController::class, 'index']);
        Route::delete('/delExerciseType/{id}', [ExerciseTypeController::class, 'destroy']);
    });

    //7-Challenge Section
    Route::prefix('challenge')->group(function () {

        Route::post('/addNewChallenge', [ChallengeController::class, 'store']);
        Route::delete('/deleteChallenge/{challenge}', [ChallengeController::class, 'destroy']);
        Route::get('/getAll', [ChallengeController::class, 'adminIndex']);
    });

    //8-Meal Section
    Route::prefix('meal')->group(function () {

        Route::post('/store', [AdminMealController::class, 'store']);
        Route::get('/getAll', [AdminMealController::class, 'getAllMeal']);
        Route::delete('/destroy/{id}', [AdminMealController::class, 'destroy']);
        Route::delete('/destroy\{id}', [AdminMealController::class, 'destroy']);
    });

    //9-Ingrediant Section
    Route::prefix('ingredient')->group(function () {

        Route::post('/store', [IngredientController::class, 'store']);
        Route::get('/getAll', [IngredientController::class, 'getAllIngredients']);
        Route::delete('/destroy/{id}', [IngredientController::class, 'destroy']);
        Route::post('/update/{id}', [IngredientController::class, 'update']);
        Route::get('/getAllIngredients', [IngredientController::class, 'getAllIngredients']);
    });

    //10-Store Section
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

    //11-Reports
    Route::group(['prefix' => 'report'], function () {
        Route::get('/userCount', [AdminController::class, 'allusers']);
        Route::post('/salesByMonth', [ReportController::class, 'salesbymonth']);
        Route::get('/refund', [ReportController::class, 'totalRefund']);
    });
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
});

//Trainer Auth & Routes
Route::prefix('trainer')->group(function () {
    Route::post('/register', [AuthController::class, 'trainerRegister']);
    Route::post('/verfiy', [AuthController::class, 'verficationRegister']);
    Route::post('/login', [AuthController::class, 'trainerLogin']);
    Route::post('/forgotPasswor', [AuthController::class, 'forgotPassword']);
    Route::post('/forgotPassword/verfiy', [AuthController::class, 'verfiyForgotPassword']);
    Route::post('/password/reset', [AuthController::class, 'passwordReset']);
    Route::post('/password/resend', [AuthController::class, 'resendCode']);
});

Route::group(['prefix' => 'trainer', "middleware" => ["auth:user", 'scope:user']], function () {
    Route::get('/logout', [AuthController::class, 'trainerLogout']);
    Route::post('/info', [UserController::class, 'trainerInfo']);
    Route::post('/password', [UserController::class, 'changePassword']);

    //1-Challenge Section
    Route::prefix('challenge')->group(function () {

        Route::post('/updateChallenge/{challenge}', [ChallengeController::class, 'update']);
        Route::get('/getAll', [ChallengeController::class, 'index']);
    });

    //2-Exercise Section
    Route::prefix('exercise')->group(function () {
        Route::post('/gender', [ExerciseController::class, 'filtering']);
        Route::get('/getall', [ExerciseController::class, 'index']);
        Route::get('/getall/{id}', [ExerciseController::class, 'show']);
    });

    //3-ExerciseType
    Route::prefix('exerciseType')->group(function () {

        Route::get('/getType', [ExerciseTypeController::class, 'index']);
        Route::get('/getType/{id}', [ExerciseTypeController::class, 'show']);
    });

    //4-Meal Section
    Route::get('meal/GetFavoritesList', [FavoriteController::class, 'GetFavoritesList']);
    Route::get('meal/AddMealToFavoritesList/{meal}', [FavoriteController::class, 'AddMealToFavoritesList']);
    Route::delete('meal/deleteFromFavorite/{meal}', [FavoriteController::class, 'deleteFromFavorite']);
    Route::get('meal/{meal}/isfav', [FavoriteController::class, 'isFavorite']);
    Route::get('meal/getAll', [AdminMealController::class, 'index']);
    Route::get('meal/byId/{id}', [AdminMealController::class, 'getById']);
    Route::get('admin/meal/latestMeals', [AdminMealController::class, 'latestMeals']);
    Route::post('admin/meal/show', [AdminMealController::class, 'show']);
    Route::get('admin/meal/popular', [AdminMealController::class, 'popularMeal']);
    Route::post('admin/meal/search', [AdminMealController::class, 'search']);
    Route::get('admin/meal/getMealsWithNoneDiet', [AdminMealController::class, 'getMealsWithNoneDiet']);
    Route::post('admin/meal/showByCategory', [AdminMealController::class, 'showByCategory']);
    Route::get('ingredient/index/{id}', [IngredientController::class, 'index']);

    //5-Media Section
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
    //Premium-section
    Route::get('coach/allCoach', [CoachAuthController::class, 'index']);
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
