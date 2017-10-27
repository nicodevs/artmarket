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
Route::apiResource('emails', 'EmailController')->only(['index', 'show']);
Route::post('images/files', 'ImageFileController@store');
Route::post('images/{image}/comments', 'CommentController@store');
Route::post('images/{image}/likes', 'LikeController@store');
Route::post('images/{image}/flag', 'ImageFlagController@store');
Route::delete('images/{image}/likes', 'LikeController@destroy');
Route::get('summary', 'SummaryController@index');
Route::post('password/recovery', 'PasswordRecoveryController@store');
Route::post('contact', 'ContactController@store');
Route::get('searches', 'SearchController@index');