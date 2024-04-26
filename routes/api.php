<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Portal_settingsController;
Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);


Route::middleware('auth:sanctum')->group( function () {
      
                // ----------------------------------- super admin routes --------------------------
          Route::group(['prefix' => 'superadmin'], function () {
   
            Route::get('/dashboard', [UserController::class, 'dashboard']);
            Route::get('/logout', [UserController::class, 'logout']);
          
                // -------------------------------- article routes --------------------------------
              Route::group(['prefix' => 'article'], function () {
             Route::get('/get', [ArticleController::class, 'get']);
             Route::get('/get/{id}', [ArticleController::class, 'getById']);
             Route::get('/delete/{id}', [ArticleController::class, 'delete']);
             Route::post('/create', [ArticleController::class, 'add']);
             Route::post('/update/{id}', [ArticleController::class, 'update']);
                });
              
                // -------------------------------- invoice routes -------------------------------
        Route::group(['prefix' => 'invoice'], function(){
            Route::get('/get', [InvoiceController::class, 'get']);
            Route::get('/get/{id}', [InvoiceController::class, 'getById']);
            Route::get('/delete/{id}', [InvoiceController::class, 'delete']);
            Route::post('/create', [InvoiceController::class, 'add']);
            Route::post('/update/{id}', [InvoiceController::class, 'update']);
            
        });
               // -------------------------------- projects routes --------------------------------
            
               Route::group(['prefix' => 'projects'], function () {
          
                Route::get('/get', [ProjectController::class, 'get_projects']);        
                Route::get('/get/{id}', [ProjectController::class, 'get']);
                Route::get('/getbyuser', [ProjectController::class, 'getbyuser']);
                Route::get('/edit/{id}', [ProjectController::class, 'edit']);
                Route::get('/delete/{id}', [ProjectController::class, 'delete']);
                Route::post('/add', [ProjectController::class, 'add']);
                Route::post('/update/{id}', [ProjectController::class, 'update']);
                
                        });
                        // -------------------------------- Settings  routes --------------------------------
                
                        Route::group(['prefix' => 'portal_settings'], function () {
                            Route::get('/get', [Portal_settingsController::class, 'get']);
                            Route::get('/get/{id}', [Portal_settingsController::class, 'getById']);
                            // Route::get('/getbyuser', [Portal_settingsController::class, 'getbyuser']);
                            Route::get('/delete/{id}', [Portal_settingsController::class, 'delete']);
                            Route::post('/create', [Portal_settingsController::class, 'add']);
                            Route::post('/update/{id}', [Portal_settingsController::class, 'update']);
                            });
                       
                        // -------------------------------- user routes --------------------------------
           
            Route::group(['prefix' => 'user'], function () {
            Route::post('/update', [UserController::class, 'update']);
            });
         
     });

        

});