<?php

use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\KindergartenController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// $lang = App\Models\Setting::where('key', 'environment')->pluck('value')->First() == 'local' ? 'en' : 'hb';
// App::setLocale($lang);
Auth::routes();

Route::middleware(['auth', 'lang'])->group(function () {
    Route::get('/', fn() => view('dashboard.index'))->name('dashboard');
    Route::get('therapy-schedule', fn() => view('dashboard.index'))->name('therapy-schedule.index');
    Route::get('tables', fn() => view('dashboard.index'))->name('tables.index');
    Route::resource('staff', StaffController::class);
    Route::resource('cluster', ClusterController::class);
    Route::resource('kindergarten', KindergartenController::class);
    Route::resource('children', ChildrenController::class);
});

Route::get('seed',function(){ \Artisan::call("db:seed"); });
Route::get('migrate',function(){ \Artisan::call("migrate"); });
Route::get('migrate-fresh',function(){ \Artisan::call("migrate:fresh"); });