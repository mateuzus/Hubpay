<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/register', 'UsersController@register');
Route::post('/login', 'UsersController@login');
Route::post('/register', 'UsersController@register');
Route::group(['middleware'=>'auth:api'],function(){
Route::post('/profile', 'UsersController@profileUpdate');
Route::post('/details', 'UsersController@details');

Route::post('/check_balance', 'AccountsController@getBalance');
Route::post('/self/transactions', 'AccountsController@getTransactions');
Route::post('/self/verifybvn', 'AccountsController@verifyBvn');

Route::post('bills/providers', 'AirtimeController@getProviders');
Route::post('airtime/purchase', 'AirtimeController@getAirtime');

Route::post('account/resolvebvn', 'IdentityController@getBvn');
Route::post('account/resolvebvn/details', 'IdentityController@getBvnFull');

Route::post('/banks/all', 'PayOutsController@getBanks');
Route::post('/bank/enquire', 'PayOutsController@getBankAccountDetails');
Route::post('/bank/transfer', 'PayOutsController@transfer');
});
