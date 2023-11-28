<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Route::get('/',[AdminController::class,'outputminga']);
Route::get('/', function () {  return view('app');})->name('application');
