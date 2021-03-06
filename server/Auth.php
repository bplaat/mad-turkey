<?php

class Auth {
    protected static function generateSession () {
        $session = md5(microtime(true) . $_SERVER['REMOTE_ADDR']);
        if (Sessions::select($session)->rowCount() == 1) {
            return static::generateSession();
        }
        return $session;
    }

    public static function revokeSession ($session) {
        Sessions::update($session, [ 'expires_at' => date('Y-m-d H:i:s') ]);
        if ($_COOKIE[SESSION_COOKIE_NAME] == $session) {
            setcookie(SESSION_COOKIE_NAME, '', time() - 3600);
            unset($_COOKIE[SESSION_COOKIE_NAME]);
        }
    }

    public static function login ($login, $password) {
        $user_query = Users::selectByLogin($login, $login);
        if ($user_query->rowCount() == 1) {
            $user = $user_query->fetch();
            if (password_verify($password, $user->password)) {
                $session = static::generateSession();
                setcookie(SESSION_COOKIE_NAME, $session, time() + SESSION_DURATION, '/', $_SERVER['HTTP_HOST'], isset($_SERVER['HTTPS']), true);
                $_COOKIE[SESSION_COOKIE_NAME] = $session;
                Sessions::insert([
                    'session' => $session,
                    'user_id' => $user->id,
                    'expires_at' => date('Y-m-d H:i:s', time() + SESSION_DURATION)
                ]);
                return true;
            }
        }
        return false;
    }

    protected static $check;
    protected static $user;
    protected static $id;

    public static function init () {
        if (isset($_COOKIE[SESSION_COOKIE_NAME])) {
            $session_query = Sessions::select($_COOKIE[SESSION_COOKIE_NAME]);
            if ($session_query->rowCount() == 1) {
                $session = $session_query->fetch();
                if (strtotime($session->expires_at) > time()) {
                    static::$check = true;
                    static::$user = Users::select($session->user_id)->fetch();
                    static::$id = $session->user_id;
                    return;
                } else {
                    static::revokeSession($session->session);
                }
            }
        }
        static::$check = false;
    }

    public static function check () {
        return static::$check;
    }

    public static function user () {
        return static::$user;
    }

    public static function id () {
        return static::$id;
    }
}
