<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\V1\AuthController;
use App\Http\Controllers\api\V1\AdminController;


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
Route::post('coach/login', [AuthController::class, 'coachLogin']);
Route::post('coach/register', [AuthController::class, 'coachRegister']);
Route::group(['prefix'=>'coach',
    "middleware" => ["auth:coach"]
], function () {
    Route::get('logout',[AuthController::class,'coachLogout']);
});

//Admin Auth & Routes
Route::post('admin/login',[AdminController::class,'login']);


//Trainer Auth & Routes
Route::post('trainer/register',[AuthController::class,'trainerRegister']);
Route::post('/verfiy',[AuthController::class,'verficationRegister']);
Route::post('trainer/login',[AuthController::class,'trainerLogin']);

Route::group(['prefix'=>'trainer',"middleware"=>["auth:user"]],function(){
    Route::get('logout',[AuthController::class,'trainerLogout']);

});
