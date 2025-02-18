<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api')->group(function () {
    Route::group(['prefix' => 'v1', 'namespace' => '\My\User\Http\Controllers\v1'], function () {
        Route::middleware('guest')->group(function () {
            Route::post('register', 'Auth\RegisteredUserController@store')->name('user.register');
//            Route::post('login', 'Auth\AuthenticatedSessionController@store')->name('user.login');
//            Route::post('forgot-password', 'Auth\PasswordResetLinkController@store')->name('user.password.forgot');
        });

//        Route::middleware(['auth:sanctum', 'setTimezone'])->group(function () {
//            Route::post('email-verify-send', 'Auth\EmailVerificationNotificationController@store')->middleware('throttle:6,1')->name('user.email.verify.send');
//            Route::post('logout', 'Auth\AuthenticatedSessionController@destroy')->name('user.logout');
//
//            Route::get('user', 'UserController@show')->name('user.show');
//        });
    });
});