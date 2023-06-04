<?php

namespace Core;

use PDO;

class DB
{
    protected static PDO|null $connection = null;

    public static function connect(): PDO
    {
        if ( is_null(static::$connection) ) {

            $dsn = "mysql:host=" . config('db.host') . ";dbname=" . config('db.database');

            $options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            static::$connection = new PDO(
                $dsn,
                config('db.user'),
                config('db.password'),
                $options
            );
        }

        return static::$connection;
    }
}
