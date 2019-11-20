<?php

class Database {
    protected static $pdo, $queryCount;

    public static function connect ($dsn, $user, $password) {
        static::$pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        static::$queryCount = 0;
    }

    public static function queryCount () {
        return static::$queryCount;
    }

    public static function lastInsertId () {
        return static::$pdo->lastInsertId();
    }

    public static function query ($query, ...$parameters) {
        static::$queryCount++;
        $statement = static::$pdo->prepare($query);
        $statement->execute($parameters);
        return $statement;
    }
}
