<?php

use Illuminate\Support\Facades\Route;

/**--------------------------------------------------------------------------------------------------------
 *                                          ORDER SECTION
---------------------------------------------------------------------------------------------------------*/
Route::namespace ('Order')->group(function () {
    Route::get('orders/{id}', 'OrderController@show')->name('orders.show');
    Route::get('orders_overview/{order}', 'OrderController@overview')->name('orders.overview');
    Route::get('orders_products/{order}', 'OrderController@products')->name('orders.products');
    Route::get('orders_shopper/{order}', 'OrderController@shopper')->name('orders.shopper');
    Route::get('orders_earner/{order}', 'OrderController@earner')->name('orders.earner');
    Route::get('orders_ticket/{order}', 'OrderController@ticket')->name('orders.tickets');
    Route::get('orders_history/{order}', 'OrderController@history')->name('orders.history');
    Route::get('orders_credit/{order}', 'OrderController@credit')->name('orders.credit');
    Route::get('orders_chat/{order}', 'OrderController@chat')->name('orders.chat');
    Route::get('orders_wish/{order}', 'OrderController@wish')->name('orders.wish');
    Route::get('orders_tracks/{order}', 'OrderController@tracks')->name('orders.tracks');
    Route::get('orders_images/{order}', 'OrderController@images')->name('orders.images');
    Route::get('orders_track_items/{order}/{origin}', 'OrderController@trackItems')->name('orders.trackItems');
    Route::get('orders', 'OrderController@index')->middleware('decode:id')->name('orders');
    Route::post('orders_reorder/{id}', 'OrderController@reorder')->name('orders.reorder');
});

/**--------------------------------------------------------------------------------------------------------------
 *                                             EXPORT SECTION
---------------------------------------------------------------------------------------------------------------*/
Route::namespace ('Export')->group(function () {
    Route::get('count_order_of_earner', 'ExportController@countOrderOfEarner')->name('earner.countOrderOfEarner');

});
