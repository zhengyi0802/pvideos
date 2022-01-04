<?php

use App\Http\Controllers\VideoCatagoryController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\ActressController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');

Route::resource('vendors', VendorController::class);

Route::resource('catagories', VideoCatagoryController::class);

Route::get('/videos/{actress}/create2', [App\Http\Controllers\VideoController::class, 'create2'])
          ->name('videos.create2');

Route::get('/videos/{video}/{actress}/destroy2', [App\Http\Controllers\VideoController::class, 'destory2'])
          ->name('videos.destory2');

Route::get('/videos/search', [App\Http\Controllers\VideoController::class, 'search'])
          ->name('videos.search');

Route::get('/videos/query', [App\Http\Controllers\VideoController::class, 'query'])
          ->name('videos.query');

Route::get('/video/browse', [App\Http\Controllers\VideoController::class, 'browse'])
          ->name('videos.browse');

Route::resource('videos', VideoController::class);

Route::resource('classifications', ClassificationController::class);

Route::get('/actresses/search', [App\Http\Controllers\ActressController::class, 'search'])
       ->name('actresses.search');

Route::resource('actresses', ActressController::class);

