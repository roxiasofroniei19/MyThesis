<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\PageController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\CustomersController;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });



Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [PageController::class, 'index'])->name('dashboard');
    Route::get('/clienti', [PageController::class, 'customers'])->name('customers');
    Route::get('/programari', [PageController::class, 'schedules'])->name('schedules');
    Route::get('/programari/istoric/{id}', [SchedulesController::class, 'schedulesHistory'])->name('schedulesHistory');
    Route::get('/programari/modifica/{id}', [SchedulesController::class, 'editScheduleView'])->name('editScheduleView');
    Route::get('/administrare', [PageController::class, 'admin'])->name('profile.admin');
    Route::get('/clienti/modifica/{id}', [CustomersController::class, 'editClientView'])->name('editClientView');
});

// API

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/getAllSchedules/{limit}', [SchedulesController::class, 'index'])->name('getAllSchedules');
    Route::get('/getTodaysSchedules', [SchedulesController::class, 'getTodaysSchedules'])->name('getTodaysSchedules');
    Route::get('/getFutureSchedules', [SchedulesController::class, 'getFutureSchedules'])->name('getFutureSchedules');
    Route::get('/getSchedulesFromStartingDate/{date}', [SchedulesController::class, 'getSchedulesFromStartingDate'])->name('getSchedulesFromStartingDate');
    Route::get('/getSchedulesBeforeEndDate/{date}', [SchedulesController::class, 'getSchedulesBeforeEndDate'])->name('getSchedulesBeforeEndDate');
    Route::get('/getSchedulesFromDateToDate/{startDate}/{endDate}', [SchedulesController::class, 'getSchedulesFromDateToDate'])->name('getSchedulesFromDateToDate');
    Route::get('/getSchedulesForDate/{date}', [SchedulesController::class, 'getSchedulesForDate'])->name('getSchedulesForDate');
    Route::get('/getTodaysSchedules/pdf', [SchedulesController::class, 'createPDF'])->name('createSchedulesPDF');
    Route::get('/getTomorrowsSchedules/pdf', [SchedulesController::class, 'createTomorrowPDF'])->name('createTomorrowSchedulesPDF');
    Route::get('/pdfSchedulesView', [SchedulesController::class, 'pdfSchedulesView'])->name('pdfSchedulesView');
    Route::get('/getTodaysSchedulesCount', [SchedulesController::class, 'getTodaysSchedulesCount'])->name('getTodaysSchedulesCount');
    Route::get('/getTomorrowsSchedulesCount', [SchedulesController::class, 'getTomorrowsSchedulesCount'])->name('getTomorrowsSchedulesCount');
    Route::get('/getAllScheduleHistory/{id}', [SchedulesController::class, 'getAllScheduleHistory'])->name('getAllScheduleHistory');
    Route::post('/addNewSchedule', [SchedulesController::class, 'store'])->name('addNewSchedule');
    Route::post('/deleteSchedule', [SchedulesController::class, 'deleteSchedule'])->name('deleteSchedule');
    Route::post('/editSchedule', [SchedulesController::class, 'editSchedule'])->name('editSchedule');
    Route::get('/sendNextDayScheduleSMS/{phone}', [SchedulesController::class, 'sendNextDayScheduleSMS'])->name('sendNextDayScheduleSMS');

    Route::get('/getAllCustomers', [CustomersController::class, 'index'])->name('getAllCustomers');
    Route::get('/getAllCustomerNames', [CustomersController::class, 'getAllCustomerNames'])->name('getAllCustomerNames');
    Route::post('/addNewCustomer', [CustomersController::class, 'store'])->name('addNewCustomer');
    Route::post('/deleteClient', [CustomersController::class, 'deleteClient'])->name('deleteClient');
    Route::post('/editClient', [CustomersController::class, 'editClient'])->name('editClient');

    Route::get('/getUserId', [CustomersController::class, 'getUserId'])->name('getUserId');
    Route::get('/getAllUsers', [CustomersController::class, 'getAllUsers'])->name('getAllUsers');
    Route::post('/addNewUser', [CustomersController::class, 'addNewUser'])->name('addNewUser');
    Route::post('/deleteUser', [CustomersController::class, 'deleteUser'])->name('deleteUser');
});
