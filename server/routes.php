<?php

Router::get('/', 'PagesController@index');
Router::get('/about', 'PagesController@about');

Router::get('/stations', 'StationsController@index');
Router::get('/stations/{Stations}', 'StationsController@show');

Router::get('/api/send_measurement', 'ApiController@sendMeasurement');

if (Auth::check()) {
    Router::get('/stations/create', 'StationsController@create');
    Router::post('/stations', 'StationsController@store');
    Router::get('/stations/{Stations}/edit', 'StationsController@edit');
    Router::post('/stations/{Stations}', 'StationsController@update');
    Router::get('/stations/{Stations}/delete', 'StationsController@delete');

    Router::get('/auth/register', 'AuthController@showRegisterForm');
    Router::post('/auth/register', 'AuthController@register');
    Router::get('/auth/logout', 'AuthController@logout');
} else {
    Router::get('/auth/login', 'AuthController@showLoginForm');
    Router::post('/auth/login', 'AuthController@login');
}

Router::fallback('PagesController@notFound');
