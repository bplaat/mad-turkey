<?php

Router::get('/', 'PagesController@index');
Router::get('/about', 'PagesController@about');

if (Auth::check()) {
    Router::get('/auth/logout', 'AuthController@logout');
} else {
    Router::get('/auth/login', 'AuthController@showLoginForm');
    Router::post('/auth/login', 'AuthController@login');
    Router::get('/auth/register', 'AuthController@showRegisterForm');
    Router::post('/auth/register', 'AuthController@register');
}

Router::fallback('PagesController@notFound');
