<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Portal_settingsController;
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
                       Route::post('/create', [ProjectController::class, 'add']);
                       Route::post('/update/{id}', [ProjectController::class, 'update']);
                           });
                           // -------------------------------- Settings  routes -------------------------------
               Route::group(['prefix' => 'portal_settings'], function () {
                               Route::get('/get', [Portal_settingsController::class, 'get']);
                               Route::get('/get/{id}', [Portal_settingsController::class, 'getById']);
                               // Route::get('/getbyuser', [Portal_settingsController::class, 'getbyuser']);
                               Route::get('/delete/{id}', [Portal_settingsController::class, 'delete']);
                               Route::post('/create', [Portal_settingsController::class, 'add']);
                               Route::post('/update/{id}', [Portal_settingsController::class, 'update']);
                               });
        // Tickets
        
        Route::group(['prefix' => 'tickets'], function () {
            Route::post('/add_tickets', [TicketController::class, 'add_tickets']);
            Route::post('/update_ticket', [TicketController::class, 'update_ticket']);
            Route::get('/get_user_ticket', [TicketController::class, 'get_user_ticket']);
            Route::get('/get_ticket/{ticketId}', [TicketController::class, 'get_ticket']);
            Route::get('/get_all_tickets', [TicketController::class, 'get_all_tickets']);

        });
      });


  Route::group(['prefix' => 'client'], function () {
    Route::post('/add', [UserController::class, 'add_client']);
    Route::post('/update', [UserController::class, 'update_client']);
    // delete client using id 
    Route::get('/delete/{id}', [UserController::class, 'delete_client']);
    Route::get('/get_client/{id}', [UserController::class, 'get_client']);
    Route::get('/get_all_clients', [UserController::class, 'get_all_clients']);
    Route::get('/get_client_detail', [UserController::class, 'get_client_detail']);
    
   // client add

  });
});