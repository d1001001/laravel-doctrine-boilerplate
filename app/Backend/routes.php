<?php

Route::group(['prefix' => 'admin'], function () {
    Route::get('login', function () {
        return View::make('backend::login');
    });
});
