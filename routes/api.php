<?php

use App\Events\CommentCreated;

Route::post('auth/signup', 'RegistrationController@store');
Route::post('auth/login', 'SessionController@store');
Route::get('auth/whoami', 'SessionController@show')->middleware('api.auth');

Route::apiResource('categories', 'CategoryController');
Route::apiResource('contents', 'ContentController');
Route::apiResource('contests', 'ContestController');
Route::apiResource('coupons', 'CouponController');
Route::apiResource('formats', 'FormatController');
Route::apiResource('frames', 'FrameController');
Route::apiResource('shippings', 'ShippingController');
Route::apiResource('slides', 'SlideController');
Route::apiResource('images', 'ImageController');
Route::apiResource('comments', 'CommentController');
Route::post('images/files', 'ImageFileController@store');
Route::post('images/{image}/comments', 'CommentController@store');
Route::post('images/{image}/likes', 'LikeController@store');
Route::delete('images/{image}/likes', 'LikeController@destroy');