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

    public static function showRegisterForm () {
        echo view('auth.register');
    }

    public static function register () {
        if (
            filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) &&
            $_POST['confirm_password'] == $_POST['password'] &&
            Auth::register($_POST['username'], $_POST['email'], $_POST['password'])
        ) {
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
