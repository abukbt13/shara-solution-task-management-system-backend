<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\WeekGoalsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user() ;
});
Route::get('auth',[UsersController::class,'auth']);

Route::group(['middleware' => ['auth:sanctum']],function (){
    Route::post('tasks',[TasksController::class,'store']);
    Route::get('show-tasks',[TasksController::class,'show']);

    Route::post('addReview',[ReviewsController::class,'store']);

});

Route::post('register',[UsersController::class, 'store']);
Route::post('login',[UsersController::class, 'login']);

Route::post('tasks/{id}',[TasksController::class,'update']);
Route::delete('tasks/{id}',[TasksController::class,'destroy']);

Route::post('weekoals',[WeekGoalsController::class,'store']);
Route::get('showWeekoals',[WeekGoalsController::class,'show']);
Route::get('getRandomWeekGoal',[WeekGoalsController::class,'getRandomWeekGoal']);


