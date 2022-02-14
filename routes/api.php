<?php

use App\Http\Controllers\FormExcel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    return $request->user();
});



Route::prefix('form')->group(function (){
    Route::post('', [FormExcel::class, 'index']);
    Route::post('userreg', [FormExcel::class, 'userreg']);
    Route::get('catmake', [FormExcel::class, 'categoryMaker']);
});