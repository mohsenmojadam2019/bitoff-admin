<?php


use App\Http\Controllers\User\UserBlockController;
use Illuminate\Support\Facades\Route;

Route::get('users', [
    'as' => 'users',
    'uses' => 'UsersController@index',
]);

Route::post('users', [
    'as' => 'users.store',
    'uses' => 'UsersController@store',
]);

//Route::get('users/{user}', [
//    'as' => 'users.show',
//    'uses' => 'UsersController@show'
//]);

Route::patch('users/{user}', [
    'as' => 'users.update',
    'uses' => 'UsersController@update',
]);
//todo
//Route::post('users/{user}/scores', [
//    'as' => 'users.scores',
//    'uses' => 'ScoresController@store',
//]);


Route::get('username', [
    'as' => 'usernames.index',
    'uses' => 'UsernamesController@index',
]);

Route::post('username', [
    'as' => 'username.store',
    'uses' => 'UsernamesController@store',
]);

Route::get('remove_vip', [\App\Http\Controllers\UsersController::class, 'removeVip'])
    ->name('remove_vip');

Route::post('user_send_notif', [\App\Http\Controllers\UsersController::class, 'sendWalletNotif'])
    ->name('send.wallet.notif');

Route::get('sync_vip_wallet', [\App\Http\Controllers\UsersController::class, 'syncVipWallet'])
    ->name('sync.vip.wallet');

Route::get('verify/email', [\App\Http\Controllers\UsersController::class, 'verificationMail'])
    ->name('verify.email');

Route::patch('users/{user}/block/update', [UserBlockController::class, 'update'])
    ->name('user.block.update');
