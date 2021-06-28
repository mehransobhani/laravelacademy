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

Route::get('/', function () {
    return redirect(route('dashboard'));
});


//Auth::routes();

//Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
//Route::post('login', 'Auth\LoginController@login');


Route::post('logout', 'admin\AuthController@logout')->name('logout');
Route::get('callback', 'api\PaymentController@callback')->name('paymentCallback');






//Route::get('/home', function () {
//    return redirect(route('dashboard'));
//})->name('home');


//Route::get('/add-categories', function (\Illuminate\Http\Request $request) {
//    $courses = \App\Models\Course::all();
//    foreach ($courses as $key => $value){
//        $value->arts()->attach($value->get_steps[0]->artID);
//    }
//    echo "success";
//});

