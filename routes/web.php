<?php

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

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', fn() => view('dashboard.index'))->name('dashboard');
    Route::get('staff', fn() => view('staff.index'))->name('staff.index');
    Route::get('staff/create', fn() => view('staff.create'))->name('staff.create');
    Route::get('children', fn() => view('children.index'))->name('children.index');
    Route::get('children/create', fn() => view('children.create'))->name('children.create');
});

Route::get('seed',function(){ \Artisan::call("db:seed"); });
Route::get('migrate',function(){ \Artisan::call("migrate"); });