<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

Route::post('/signup', [MainController::class, 'signup']);
Route::post('/login', [MainController::class, 'login']);


Route::middleware('auth:sanctum')->group( function () {
    Route::get('/dashboard', [MainController::class, 'dashboard']);
    Route::get('/logout', [MainController::class, 'logout']);
    

});