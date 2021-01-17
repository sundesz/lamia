<?php

namespace orderhandling\Classes\Connection;

use \PDO;

class Dbh
{

    private $host = '127.0.0.1';
    private $user = 'kassa_user';
    private $pwd = 'kassa_developer';
    private $dbName = 'order_management';

    protected function __construct()
    {
    }

    protected function connect()
    {
        try {
            $dsn = "pgsql:host={$this->host};dbname={$this->dbName}";
            $pdo = new PDO($dsn, $this->user, $this->pwd);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
    }
}
