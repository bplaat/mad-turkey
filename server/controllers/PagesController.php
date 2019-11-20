<?php

class PagesController {
    public static function index () {
        echo view('index');
    }

    public static function about () {
        echo view('about');
    }

    public static function notfound () {
        http_response_code(404);
        echo view('notfound');
    }
}
