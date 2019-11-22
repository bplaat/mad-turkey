<?php

class Users extends Model {
    public static function selectByLogin ($username, $email) {
        return Database::query('SELECT * FROM users WHERE `username` = ? OR `email` = ?', $username, $email);
    }
}
