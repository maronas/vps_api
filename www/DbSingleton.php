<?php

class DbSingleton
{
    private static ?DbSingleton $instance = null;
//    private mysqli $connection;

    protected function __construct()
    {
        $servername = 'db';
        $username = 'interneto_vizija';
        $password = 'slaptazodis';
        $database = 'time4vps_orders';
        $this->connection = new mysqli($servername, $username, $password, $database);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public static function getInstance(): DbSingleton
    {
        if (self::$instance == null) {
            self::$instance = new DbSingleton();
        }

        return self::$instance;
    }

    public static function getConnection(): mysqli
    {
        return self::getInstance()->connection;
    }
}