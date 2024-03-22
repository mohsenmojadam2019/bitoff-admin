<?php

use Bitoff\Feedback\Application\Models\Level;
use Illuminate\Support\Facades\Route;

require __DIR__ . ('/user/user.php');

// Route::get('/', [
//     'as' => 'home',
//     'uses' => 'HomeController@basic',
// ]);

Route::get('orders', [
    'as' => 'orders',
    'middleware' => 'decode:id',
    'uses' => 'OrdersController@index',
]);

Route::get('orders', [
    'as' => 'orders.show',
    'middleware' => 'decode:id',
    'uses' => 'OrdersController@show',
]);

Route::post('orders/{order}/resolve', [
    'as' => 'orders.resolve',
    'uses' => 'OrdersController@resolve',
]);

Route::delete('orders/{order}/eaner', [
    'as' => 'orders.cancel.earner',
    'uses' => 'OrdersController@removeEarner',
]);

Route::delete('orders/{order}/shopper', [
    'as' => 'orders.cancel.shopper',
    'uses' => 'OrdersController@cancel',
]);

Route::delete('orders/{order}/items/{itemId}', [
    'as' => 'orders.items.cancel',
    'uses' => 'OrdersController@cancelItem',
])->middleware(['throttle:1']);

Route::post('orders/{order}/items/{itemId}/deliver', [
    'as' => 'orders.items.deliver',
    'uses' => 'OrdersController@deliver',
]);

Route::post('orders/{order}/items/{itemId}/tracking', [
    'as' => 'orders.items.tracking',
    'uses' => 'OrdersController@tracking',
]);

Route::patch('orders/{order}/tracking/{itemId}/', [
    'as' => 'orders.update.tracking',
    'uses' => 'OrdersController@updateTracking',
]);

Route::post('orders/{order}/reserves/{reserve_id}', [
    'as' => 'orders.chat.store',
    'uses' => 'OrdersController@storeChat',
]);

Route::get('tickets', [
    'as' => 'tickets',
    'uses' => 'TicketsController@index',
]);

Route::get('ticket/replies/{ticket}', [
    'as' => 'tickets.get-replies',
    'uses' => 'TicketsController@replies',
]);

Route::post('ticket/replies', [
    'as' => 'tickets.store-reply',
    'uses' => 'TicketsController@storeReply',
]);

Route::get('transactions', [
    'as' => 'transactions',
    'uses' => 'TransactionsController@index',
]);

Route::post("transactions/{transaction}/confirm", [
    'as' => 'transactions.confirm',
    'uses' => 'TransactionsController@confirm',
]);

Route::post("transactions/{transaction}/manual", [
    'as' => 'transactions.manual',
    'uses' => 'TransactionsController@manual',
]);

Route::get('settings', [
    'as' => 'settings',
    'uses' => 'SettingsController@index',
]);

Route::post('settings/{setting}', [
    'as' => 'settings.store',
    'uses' => 'SettingsController@store',
]);

Route::patch('settings/{setting}', [
    'as' => 'settings.update',
    'uses' => 'SettingsController@update',
]);

Route::get('products', [
    'as' => 'products',
    'uses' => 'ProductsController@index',
]);
Route::get('products/{product}', [
    'as' => 'products.show',
    'uses' => 'ProductsController@show',
]);

Route::get('areas', [
    'as' => 'areas.index',
    'uses' => 'AreaController@index',
]);

Route::get('areas/{state}/cities', [
    'as' => 'areas.cities',
    'uses' => 'AreaController@show',
]);

Route::get('roles', [
    'as' => 'acl.roles',
    'uses' => 'AclController@showAllRoles',
]);

Route::get('roles/create', [
    'as' => 'acl.roles.create',
    'uses' => 'AclController@createRole',
]);

Route::post('roles', [
    'as' => 'acl.roles.store',
    'uses' => 'AclController@storeRole',
]);

Route::get('roles/{role}', [
    'as' => 'acl.roles.edit',
    'uses' => 'AclController@editRole',
]);

Route::patch('roles/{role}', [
    'as' => 'acl.roles.update',
    'uses' => 'AclController@updateRole',
]);

Route::get('report/show/{category}', [
    'as' => 'report.show',
    'uses' => 'ReportController@show',
]);

Route::post('report/filter/{category}', [
    'as' => 'report.filter',
    'uses' => 'ReportController@filter',
]);

Route::get('contacts', [
    'as' => 'contacts',
    'uses' => 'ContactsController@index',
]);

Route::get('activities', [
    'as' => 'activities',
    'uses' => 'LogsController@index',
]);

Route::post('refresh', [
    'as' => 'pay.refresh',
    'uses' => 'HomeController@refresh',
]);

Route::get('pay', [
    'as' => 'pay.show',
    'uses' => 'HomeController@index',
]);

Route::get('users/{id}', [
    'as' => 'users.show',
    'uses' => 'UserReportController',
]);

Route::get('tickets/create', [
    'as' => 'tickets.create',
    'uses' => 'TicketsController@create',
]);

Route::post('tickets', [
    'as' => 'tickets.store',
    'uses' => 'TicketsController@store',
]);

Route::get('reports/{type}', [
    'as' => 'reports.show',
    'uses' => 'ReportController@show',
]);

Route::get('sync/wallet/btc', [
    'as' => 'sync.wallet.bitcoin',
    'uses' => 'WalletController@syncBtc',
]);

Route::get('sync/wallet/usdt', [
    'as' => 'sync.wallet.usdt',
    'uses' => 'WalletController@syncUsdt',
]);

Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->name('dashboard');

Route::namespace('Reports')->group(function () {
    Route::get('reports_user', [\App\Http\Controllers\Reports\UsersReportController::class, 'index'])
        ->name('reports.users');

    Route::get('register_user_last_month', [\App\Http\Controllers\Reports\UsersReportController::class, 'register'])
        ->name('register.user.report');

    Route::get('reports_order', [\App\Http\Controllers\Reports\OrdersReportController::class, 'index'])
        ->name('reports.orders');
});
