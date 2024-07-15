<?php

use App\Http\Controllers\TableauProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum','tableau.token']], function() {
    Route::get('get-projects', [TableauProjectController::class, 'getProjects']);
    Route::get('get-workbooks/{projectName}', [TableauProjectController::class, 'getWorkbooks']);
    Route::get('get-views/{projectName}', [TableauProjectController::class, 'getViews']);
    Route::post('get-view', [TableauProjectController::class, 'getViewData']);
    Route::get('get-view/{viewId}', [TableauProjectController::class, 'getViewDataWithViewId']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
