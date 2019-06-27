<?php

use Illuminate\Support\Facades\Route;

Route::get('signup', 'Web\\Auth\\SignupController@index');
Route::post('signup', 'Web\\Auth\\SignupController@store');
Route::get('login', 'Web\\Auth\\LoginController@index')->name('login');
Route::post('login', 'Web\\Auth\\LoginController@store');

Route::get('invite/employee/{link}', 'Web\\Auth\\UserInvitationController@check');
Route::post('invite/employee/{link}/join', 'Web\\Auth\\UserInvitationController@join');
Route::post('invite/employee/{link}/accept', 'Web\\Auth\\UserInvitationController@accept');

Route::middleware(['auth'])->group(function () {
    Route::get('logout', 'Web\\Auth\\LoginController@logout');

    // used to show the secret key to the user
    Route::get('welcome', 'Web\\Auth\\WelcomeController@index');

    // used to set the secret key in the cookies
    Route::get('secret', 'Web\\Auth\\SecretController@index');
    Route::post('secret', 'Web\\Auth\\SecretController@store');

    Route::middleware(['secret.key'])->group(function () {
        Route::get('home', 'Web\\Home\\HomeController@index')->name('home');
        Route::post('search/name', 'Web\\Search\\ContactNameSearchController@index');

        Route::resource('contacts', 'Web\\Contact\\ContactController');
        Route::resource('company', 'Company\\CompanyController')->only(['create', 'store']);
    });
});
