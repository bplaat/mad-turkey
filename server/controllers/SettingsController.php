<?php

class SettingsController {
    public static function showSettingsForm() {
        $sessions = Sessions::select([ 'user_id' => Auth::id() ])->fetchAll();
        $active_sessions = [];
        foreach ($sessions as $session) {
            if (strtotime($session->expires_at) > time()) {
                $active_sessions[] = $session;
            }
        }
        echo view('auth.settings', [ 'sessions' => $active_sessions ]);
    }

    public static function changeDetails () {
        if (
            filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
        ) {
            Users::update(Auth::id(), [
                'username' => $_POST['username'],
                'email' => $_POST['email']
            ]);
            Router::redirect('/settings');
        }
        Router::back();
    }

    public static function changePassword () {
        if (
            $_POST['new_password'] == $_POST['confirm_new_password']
        ) {
            if (password_verify($_POST['old_password'], Auth::user()->password)) {
                Users::update(Auth::id(), [
                    'password' => password_hash($_POST['new_password'], PASSWORD_DEFAULT)
                ]);
                Router::redirect('/settings');
            }
        }
        Router::back();
    }

    public static function revokeSession ($session) {
        if ($session->user_id == Auth::id()) {
            Auth::revokeSession($session->session);
            Router::redirect('/settings');
        }
        Router::back();
    }

    public static function deleteAccount () {
        Auth::revokeSession($_COOKIE[SESSION_COOKIE_NAME]);
        Users::delete(Auth::id());
        Sessions::delete([ 'user_id' => Auth::id() ]);
        Router::redirect('/');
    }
}
