<?php

use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\UserProjects;
use App\Http\Controllers\YearGoalsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\WeekGoalsController;
use Illuminate\Routing\MiddlewareNameResolver;
use App\Http\Controllers\ProjectUsersController;


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


Route::group(['middleware' => ['auth:sanctum']],function (){
//  Route::middleware(['auth', 'user'])->group(function () {
//        Route::controller(RolesController::class)->group(function (){
//
//        Route::post('addroles',  'store')->where('name','superadmin');
//        Route::get('getroles','list')->where('name',['admin','superadmin']);
//
//        });
//    });


    Route::get('user-auth',[UsersController::class,'auth']);

    Route::get('show_admins',[UsersController::class,'show_admins']);
    Route::get('show_users',[UsersController::class,'show_users']);
    Route::post('users/update-user/{id}',[UsersController::class,'update_user']);

    Route::get('show-tasks/{id}',[TasksController::class,'show']);
    Route::get('users_to_be_added_to_task/{id}',[TasksController::class,'users_to_be_added_to_task']);
    Route::post('create_task/{id}',[TasksController::class,'create_task']);
    Route::post('tasks',[TasksController::class,'store']);
    Route::post('addReview',[ReviewsController::class,'store']);



//    user Tasks
    Route::get('show_user_tasks',[TasksController::class,'show_user_tasks']);
    Route::get('show_user_completed_tasks',[TasksController::class,'show_user_completed_tasks']);






    Route::get('get-reviews',[TasksController::class,'get_reviews']);
    Route::get('mark_completed/{id}',[TasksController::class,'mark_completed']);

    Route::get('edit-tasks/{id}',[TasksController::class,'edit']);
    Route::post('update-tasks/{id}',[TasksController::class,'updateone']);

    Route::get('logout',[UsersController::class, 'logout']);
    Route::post('create_users',[UsersController::class, 'registerAdmin']);
    Route::get('getProfile',[UsersController::class, 'profile']);

     Route::post('addroles',[RolesController::class,'store']);
     Route::get('getroles',[RolesController::class,'list']);


     Route::post('add_project',[ProjectsController::class,'store']);
     Route::post('edit_project/{id}',[ProjectsController::class,'edit']);
     Route::get('list_users_not_in_project/{project_id}',[ProjectsController::class,'list_users_not_in_project']);
    Route::post('add_user_to_the_current_project',[ProjectsController::class,'add_user_to_the_current_project']);
    Route::get('fetch_users_to_the_current_project/{id}',[ProjectsController::class,'fetch_users_to_the_current_project']);


    Route::get('getprojects',[ProjectsController::class,'listProjects']);
     Route::get('fetch_overview/{id}',[ProjectsController::class,'fetch_overview']);

     Route::post('assignuserproject', [ProjectUsersController::class, 'addUserToProject']);
     Route::get('unassignedUsers',[ProjectUsersController::class,'unassignedUsers']);
     Route::get('fetchUserProjects',[ProjectUsersController::class,'fetchUserProjects']);
<<<<<<< HEAD
     Route::get('usersNotAssignedTask/{project_id}',[ProjectUsersController::class,'usersNotAssignedTask']);
    
=======

>>>>>>> 06feae43e831c62fb92faa1b23600f7f0b7c52e5
//     weekly goals
    Route::post('create_goal',[WeekGoalsController::class,'store']);
    Route::post('create_yearly_goal',[YearGoalsController::class,'store_yeargoal']);

//    save Youtube video
    Route::post('save_youtube_video',[\App\Http\Controllers\YouTubeController::class,'create']);
    Route::get('show_youtube_video',[\App\Http\Controllers\YouTubeController::class,'show']);

    /*
     * Upload local document
     */
    Route::post('upload_local_document',[DocumentsController::class,'create_local']);
    Route::get('show_local_documents',[DocumentsController::class,'show_local_documents']);
    /*
     * upload google documents
     */
    Route::post('upload_google_document',[DocumentsController::class,'create_google']);
    Route::get('show_google_documents',[DocumentsController::class,'show_google_documents']);


    Route::get('trashed_tasks',[TasksController::class,'trashed_tasks']);

    /*
     * Projects for users
     */
    Route::get('projects/active_tasks/{id}',[UserProjects::class,'active_tasks']);
    Route::get('projects/pendind_tasks',[UserProjects::class,'pendind_tasks']);
    Route::get('projects/completed_tasks',[UserProjects::class,'completed_tasks']);
    Route::get('projects/revision_tasks',[UserProjects::class,'revision_tasks']);
    Route::post('projects/mark_complete/{id}',[UserProjects::class,'mark_complete']);

});


Route::post('register',[UsersController::class, 'store']);
Route::post('login',[UsersController::class, 'login']);


Route::post('tasks/{id}',[TasksController::class,'update']);
Route::get('delete_tasks/{id}',[TasksController::class,'destroy']);

Route::post('weekoals',[WeekGoalsController::class,'store']);
Route::get('showWeekoals',[WeekGoalsController::class,'show']);
Route::get('getRandomWeekGoal',[WeekGoalsController::class,'getRandomWeekGoal']);
Route::get('getRandomYearGoal',[WeekGoalsController::class,'getRandomYearGoal']);
Route::post('company',[WeekGoalsController::class,'storeCompany']);


