<?php

class Router {
    public static function get ($route, $callback) {
        static::match(['get'], $route, $callback);
    }

    public static function post ($route, $callback) {
        static::match(['post'], $route, $callback);
    }

    public static function any ($route, $callback) {
        static::match(['get', 'post'], $route, $callback);
    }

    public static function match ($methods, $route, $callback) {
        if (
            in_array(strtolower($_SERVER['REQUEST_METHOD']), $methods) &&
            preg_match('#^' . preg_replace('/{.*}/U', '([^/]*)', $route) . '$#', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $matches)
        ) {
            array_shift($matches);
            preg_match_all('/{(.*)}/U', $route, $vars);
            foreach ($vars[1] as $index => $var) {
                if ($var != '') {
                    $query = call_user_func($var . '::select', $matches[$index]);
                    if ($query->rowCount() == 1) {
                        $matches[$index] = $query->fetch();
                    } else {
                        return;
                    }
                }
            }
            call_user_func(str_replace('@', '::', $callback), ...$matches);
            exit;
        }
    }

    public static function fallback ($callback) {
        call_user_func(str_replace('@', '::', $callback));
        exit;
    }

    public static function redirect ($route) {
        header('Location: ' . $route);
        exit;
    }

    public static function back () {
        static::redirect($_SERVER['HTTP_REFERER']);
    }
}
