<?php

use App\Http\Controllers\GraphController;
use App\Http\Controllers\ScraperController;
use Illuminate\Support\Facades\Auth;
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
    return view('auth.login');
});

Auth::routes();

// running commands
// Route::get('/command/scraper', [ScraperController::class, 'runScraper'])->name('run.scraper');
// routes
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/get', [GraphController::class, 'list'])->name('list.sentiments');
Route::get('/get/monthly/{month}', [GraphController::class, 'listMonthly'])->name('list.monthly.sentiments');
Route::get('/get/hourly/{selected_date}', [GraphController::class, 'listHourly'])->name('list.hourly.sentiments');
Route::get('/get/live', [GraphController::class, 'showLiveHour'])->name('show.live.hour.sentiments');
