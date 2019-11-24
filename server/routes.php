<?php

Router::get('/', 'PagesController@index');

Router::get('/api/send_measurement', 'ApiController@sendMeasurementGet');
Router::post('/api/send_measurement', 'ApiController@sendMeasurementPost');

if (Auth::check()) {
    Router::get('/stations', 'StationsController@index');
    Router::get('/stations/create', 'StationsController@create');
    Router::post('/stations', 'StationsController@store');
    Router::get('/stations/{Stations}', 'StationsController@show');
    Router::get('/stations/{Stations}/edit', 'StationsController@edit');
    Router::post('/stations/{Stations}', 'StationsController@update');
    Router::get('/stations/{Stations}/delete', 'StationsController@delete');
    Router::get('/stations/{Stations}/(.*)', 'StationsController@showByDay');

    Router::get('/events', 'EventsController@index');
    Router::get('/events/create', 'EventsController@create');
    Router::post('/events', 'EventsController@store');
    Router::get('/events/{Events}', 'EventsController@show');
    Router::get('/events/{Events}/edit', 'EventsController@edit');
    Router::post('/events/{Events}', 'EventsController@update');
    Router::get('/events/{Events}/delete', 'EventsController@delete');

    Router::get('/settings', 'SettingsController::showSettingsForm');
    Router::post('/settings/change_details', 'SettingsController::changeDetails');
    Router::post('/settings/change_password', 'SettingsController::changePassword');
    Router::get('/settings/revoke_session/{Sessions}', 'SettingsController::revokeSession');
    Router::get('/settings/delete', 'SettingsController::deleteAccount');

    Router::get('/auth/register', 'AuthController@showRegisterForm');
    Router::post('/auth/register', 'AuthController@register');
    Router::get('/auth/logout', 'AuthController@logout');
} else {
    Router::get('/auth/login', 'AuthController@showLoginForm');
    Router::post('/auth/login', 'AuthController@login');
}

Router::fallback('PagesController@notFound');
