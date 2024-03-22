<?php

use Illuminate\Support\Facades\Route;
use Bitoff\Mantis\Application\Http\Controllers\OfferController;
use Bitoff\Mantis\Application\Http\Controllers\PaymentMethodController;
use Bitoff\Mantis\Application\Http\Controllers\PaymentMethodToggleController;
use Bitoff\Mantis\Application\Http\Controllers\SettingController;
use Bitoff\Mantis\Application\Http\Controllers\TagController;
use Bitoff\Mantis\Application\Http\Controllers\TradeCancelController;
use Bitoff\Mantis\Application\Http\Controllers\TradeController;
use Bitoff\Mantis\Application\Http\Controllers\TradeDisputeResolveController;
use Bitoff\Mantis\Application\Http\Controllers\TradeSendCryptoController;

Route::get('offers', [OfferController::class, 'index'])->name('offers.index');
Route::get('offers/{offer}', [OfferController::class, 'show'])->name('offers.show');
Route::get('offers/{offer}/overview', [OfferController::class, 'overview'])->name('offers.overview');
Route::get('offers/{offer}/offerer', [OfferController::class, 'offerer'])->name('offers.offerer');
Route::get('offers/{offer}/trades', [OfferController::class, 'trades'])->name('offers.trades');
Route::get('offers/{offer}/credits', [OfferController::class, 'credits'])->name('offers.credits');
Route::get('offers/{offer}/history', [OfferController::class, 'history'])->name('offers.history');

Route::get('trades', [TradeController::class, 'index'])->name('trades.index');
Route::get('trades/{trade}', [TradeController::class, 'show'])->name('trades.show');
Route::get('trades/{trade}/overview', [TradeController::class, 'overview'])->name('trades.overview');
Route::get('trades/{trade}/offerer', [TradeController::class, 'offerer'])->name('trades.offerer');
Route::get('trades/{trade}/trader', [TradeController::class, 'trader'])->name('trades.trader');
Route::get('trades/{trade}/history', [TradeController::class, 'history'])->name('trades.history');
Route::get('trades/{trade}/credits', [TradeController::class, 'credits'])->name('trades.credits');
Route::get('trades/{trade}/tickets', [TradeController::class, 'tickets'])->name('trades.tickets');

Route::post('trades/{trade}/resolve', TradeDisputeResolveController::class)->name('trades.resolve');
Route::patch('trades/{trade}/cancel', TradeCancelController::class)->name('trades.cancel');
Route::patch('trades/{trade}/send-crypto', TradeSendCryptoController::class)->name('trades.send_crypto');

Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment_methods.index');
Route::post('payment-methods', [PaymentMethodController::class, 'store'])->name('payment_methods.store');
Route::get('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'show'])->name('payment_methods.show');
Route::get('payment-methods/{paymentMethod}/update', [PaymentMethodController::class, 'showUpdate'])->name('payment_methods.update.show');
Route::patch('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment_methods.update');
Route::patch('payment-methods/toggle/{paymentMethod}', PaymentMethodToggleController::class)->name('payment_methods.toggle');

Route::post('tags', [TagController::class, 'store'])->name('tags.store');

Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
Route::patch('settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
