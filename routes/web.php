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
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');
    
    Route::get('staff', function () {
        return view('staff.index');
    })->name('staff.index');
    
    Route::get('children', function () {
        return view('children.index');
    })->name('children.index');
    Route::get('children-create', function () {
        return view('children.create');
    })->name('children.create');
});