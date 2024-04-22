<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
<<<<<<< HEAD
use App\Http\Controllers\TicketController;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {
    Route::group(['prefix' => 'superadmin'], function () {
   
        Route::get('/dashboard', [UserController::class, 'dashboard']);
        Route::get('/logout', [UserController::class, 'logout']);
        // user
        Route::group(['prefix' => 'user'], function () {
          Route::post('/update', [UserController::class, 'update']);
        });
        
        // Tickets
        
        Route::group(['prefix' => 'tickets'], function () {
            Route::post('/add_tickets', [TicketController::class, 'add_tickets']);
            Route::post('/update_ticket', [TicketController::class, 'update_ticket']);
            Route::get('/get_user_ticket', [TicketController::class, 'get_user_ticket']);
            Route::get('/get_ticket/{ticketId}', [TicketController::class, 'get_ticket']);
            Route::get('/get_all_tickets', [TicketController::class, 'get_all_tickets']);
=======
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use App\Http\Controllers\ProjectController;
Route::post('/signup', [UserController::class, 'signup']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group( function () {
      
          Route::group(['prefix' => 'superadmin'], function () {
   
            Route::get('/dashboard', [UserController::class, 'dashboard']);
            Route::get('/logout', [UserController::class, 'logout']);
            // user
            Route::group(['prefix' => 'user'], function () {
            Route::post('/update', [UserController::class, 'update']);
            });
            
            // Tickets
            
            Route::group(['prefix' => 'user'], function () {
                Route::post('/update', [UserController::class, 'update']);
              
            });
        
            // projects
            
        Route::group(['prefix' => 'projects'], function () {
          
        Route::get('/get', [ProjectController::class, 'get_projects']);        
        Route::get('/get/{id}', [ProjectController::class, 'get']);
        Route::get('/getbyuser', [ProjectController::class, 'getbyuser']);
        Route::get('/edit/{id}', [ProjectController::class, 'edit']);
        Route::get('/delete/{id}', [ProjectController::class, 'delete']);
        Route::post('/add', [ProjectController::class, 'add']);
        Route::post('/update/{id}', [ProjectController::class, 'update']);
        
                });
        
           
        });
        
>>>>>>> daniyal





          
        });
   });


});
