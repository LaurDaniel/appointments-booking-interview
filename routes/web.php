<?php

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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/admin/get-appointments', [App\Http\Controllers\HomeController::class, 'getAppointmentsAdmin']);
    Route::get('/get-appointments', [App\Http\Controllers\HomeController::class, 'getAppointments']);
    Route::get('/booking', [App\Http\Controllers\BookingController::class, 'index'])->name('booking');
    Route::get('/get-events', [App\Http\Controllers\BookingController::class, 'getEvents'])->name('events');
    Route::post('/get-available-hours', [App\Http\Controllers\BookingController::class, 'getHours'])->name('event.hours');
    Route::post('/book-appointment', [App\Http\Controllers\BookingController::class, 'bookAppointment'])->name('event.book');



});
