<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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





          
        });
   });


});
