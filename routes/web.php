<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AnalyticMiddleware;


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


// Route::get('/analytics/login', 'AnalyticsController@loginIndex')->name('analytics.login');
// Route::post('/analytics/login', 'AnalyticsController@loginPost')->name('analytics.login.post');

// Route::middleware('auth')->group(function () {
//     Route::get('/analytics/{lang}', 'AnalyticsController@index')->name('analytics');
//     Route::get('/analytics', 'AnalyticsController@indexLang')->name('analytics.index');
// });




Route::middleware([AnalyticMiddleware::class])->group(function () {

    Route::redirect('/', '/new-note')->name('home');


    Route::get('/new-note', 'MainController@index')->name('new.note');
    Route::post('/new-note', 'MainController@create')->name('note.create');

    Route::get('/note/{slug}', 'MainController@show')->name('note.display');
    Route::post('/note/{slug}', 'MainController@decrypt')->name('note.decrypt');



    Route::fallback(function () {
        return redirect()->route('home');
    });
});