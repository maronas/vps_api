<?php
class PDOSingleton
{
    private static ?PDOSingleton $instance = null;
    private PDO $connection;

    protected function __construct()
    {
        $db_info = array(
            'db_host' => 'db',
            'db_user' => 'interneto_vizija',
            'db_pass' => 'slaptazodis',
            'db_name' => 'time4vps_orders'
        );
        try {
            $this -> connection = new PDO("mysql:host=" . $db_info['db_host'] . ';dbname=' . $db_info['db_name'], $db_info['db_user'], $db_info['db_pass']);
        } catch (PDOException $error) {
            echo $error->getMessage();
        }
    }

    public static function getInstance(): PDOSingleton
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function getConnection(): PDO
    {
        return self::getInstance()->connection;
    }
}