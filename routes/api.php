<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use App\Http\Controllers\ProjectController;
Route::post('/signup', [MainController::class, 'signup']);
Route::post('/login', [MainController::class, 'login']);
Route::middleware('auth:sanctum')->get('/doshboard', function (Request $request) {
  Route::group(['prefix' => 'superadmin'], function () {
   
    Route::get('/dashboard', [MainController::class, 'dashboard']);
    Route::get('/logout', [MainController::class, 'logout']);
    // user
    Route::group(['prefix' => 'user'], function () {
    Route::post('/update', [MainController::class, 'update']);
    });
    
    // Tickets
    
    Route::group(['prefix' => 'user'], function () {
        Route::post('/update', [MainController::class, 'update']);
      
    });

    // projects
    
    Route::group(['prefix' => 'projects', 'middleware' => 'api'], function () {
      Route::get('/get', [ProjectController::class, 'get_projects']);        
      Route::get('/get/{id}', [ProjectController::class, 'get']);
      Route::get('/getbyuser/{user_id}', [ProjectController::class, 'getbyuser']);
      Route::get('/edit/{id}', [ProjectController::class, 'edit']);
      Route::get('/delete/{id}', [ProjectController::class, 'delete']);
      Route::post('/add', [ProjectController::class, 'add']);
      Route::post('/update/{id}', [ProjectController::class, 'update']);
  });
  
    

});
  return $request->user();
});