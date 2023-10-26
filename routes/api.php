<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\UserProfileController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Public Routes
Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);


    
//Protected Routes
Route::group(['middleware' => 'auth:sanctum'],function(){
    //userSetting api's
Route::apiResource('/user-setting',UserSettingsController::class);

//User Profile
Route::get('user-profile',[UserProfileController::class , 'index']);
Route::patch('update-user-profile/{id}',[UserProfileController::class , 'update']);

//Logout
Route::get('/logout',[AuthController::class,'logout']);
});