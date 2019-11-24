<?php

class AuthController {
    public static function showLoginForm () {
        echo view('auth.login');
    }

    public static function login () {
        if (Auth::login($_POST['login'], $_POST['password'])) {
            Router::redirect('/');
        } else {
            Router::back();
        }
    }

    public static function logout () {
        Auth::revokeSession($_COOKIE[SESSION_COOKIE_NAME]);
        Router::redirect('/auth/login');
    }
}
