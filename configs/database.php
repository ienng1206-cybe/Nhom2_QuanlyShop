<?php

if (!function_exists('db_connect')) {
    function db_connect(): PDO
    {
        static $pdo = null;
        if ($pdo instanceof PDO) {
            return $pdo;
        }

        $host = DB_HOST;
        $port = DB_PORT;
        $db = DB_NAME;
        $charset = DB_CHARSET;

        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
        $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

        return $pdo;
    }
}
