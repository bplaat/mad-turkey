<?php

class AdminController {
    public static function index () {
        $users = Users::select()->fetchAll();
        echo view('admin.index', [ 'users' => $users ]);
    }

    public static function create () {
        echo view('admin.create');
    }

    public static function store () {
        if (
            filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) &&
            Users::selectByLogin($_POST['username'], $_POST['email'])->rowCount() == 0 &&
            $_POST['password'] == $_POST['confirm_password']
        ) {
            Users::insert([
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => $_POST['role']
            ]);
            Router::redirect('/admin/' . Database::lastInsertId());
        }
        Router::back();
    }

    public static function show ($user) {
        echo view('admin.show', [ 'user' => $user ]);
    }

    public static function edit ($user) {
        echo view('admin.edit', [ 'user' => $user ]);
    }

    public static function update ($user) {
        if (
            filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
        ) {
            Users::update($user->id, [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'role' => $_POST['role']
            ]);

            if (
                $_POST['new_password'] != '' && $_POST['confirm_new_password'] != '' &&
                $_POST['new_password'] == $_POST['confirm_new_password']
            ) {
                Users::update($user->id, [
                    'password' => password_hash($_POST['new_password'], PASSWORD_DEFAULT)
                ]);
            }

            Router::redirect('/admin/' . $user->id);
        }
        Router::back();
    }

    public static function delete ($user) {
        Sessions::delete([ 'user_id' => $user->id ]);
        Users::delete($user->id);
        Router::redirect('/admin');
    }
}
