<?php

declare(strict_types=1);

use App\Http\Controllers\GiftController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/guest', [SessionController::class, 'createGuest'])->name('session.guest.create');
Route::post('/locale/{locale}', LocaleController::class)
    ->whereIn('locale', ['fr', 'en'])
    ->name('locale.update');

Route::post('/session/profile/{profile}', [SessionController::class, 'profile'])->name('session.profile');
Route::post('/session/guest', [SessionController::class, 'guest'])->name('session.guest');
Route::delete('/session', [SessionController::class, 'destroy'])->name('session.destroy');

Route::resource('profiles', ProfileController::class);

Route::post('/profiles/{profile}/gifts', [GiftController::class, 'store'])->name('profiles.gifts.store');
Route::put('/profiles/{profile}/gifts/{gift}', [GiftController::class, 'update'])->name('profiles.gifts.update');
Route::delete('/profiles/{profile}/gifts/{gift}', [GiftController::class, 'destroy'])->name('profiles.gifts.destroy');
Route::post('/profiles/{profile}/gifts/{gift}/reservation', [GiftController::class, 'reserve'])->name('profiles.gifts.reserve');
Route::delete('/profiles/{profile}/gifts/{gift}/reservation', [GiftController::class, 'cancelReservation'])->name('profiles.gifts.cancel-reservation');
