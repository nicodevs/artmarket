<?php

Route::post('auth/signup', 'RegistrationController@store');
Route::post('auth/login', 'SessionController@store');
Route::get('auth/whoami', 'SessionController@show')->middleware('api.auth');

Route::apiResource('categories', 'CategoryController');
Route::apiResource('contents', 'ContentController');
Route::apiResource('contests', 'ContestController');
Route::apiResource('coupons', 'CouponController');
Route::apiResource('frames', 'FrameController');
