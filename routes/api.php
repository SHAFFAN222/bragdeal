<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use App\Http\Controllers\ProjectController;
Route::post('/signup', [MainController::class, 'signup']);
Route::post('/login', [MainController::class, 'login']);
Route::get('/projects/get/{id}', [ProjectController::class, 'get_projects'])->name('projects.get');
Route::post('/projects_store', [ProjectController::class, 'projects_store'])->name('projects_store');

Route::middleware('auth:sanctum')->group( function () {
    Route::get('/dashboard', [MainController::class, 'dashboard']);
    Route::get('/logout', [MainController::class, 'logout']);
    

});