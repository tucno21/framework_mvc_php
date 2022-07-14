<?php

namespace System;

use mysqli;

// require_once dirname(__DIR__) . '/Config/App.php';
// dd($db_host);

class ConnectDB
{
    private static $db_host;
    private static $db_user;
    private static $db_pass;
    private static $db_name;
    private static $db_charset;
    public $conn;

    public function __construct()
    {
        $this->db_open();
    }

    public static function dataBase($db_host, $db_user, $db_pass, $db_name, $db_charset)
    {
        self::$db_host = $db_host;
        self::$db_user = $db_user;
        self::$db_pass = $db_pass;
        self::$db_name = $db_name;
        self::$db_charset = $db_charset;
    }

    public function db_open()
    {
        try {
            $mysqli = new mysqli(self::$db_host, self::$db_user, self::$db_pass, self::$db_name);
            $mysqli->set_charset(self::$db_charset);
            $this->conn = $mysqli;
        } catch (Exception $e) { //Catch exception
            echo 'La conexión a la base de datos falló: ' . $e->getMessage();
            echo "<br>";
            echo "error de depuración: \n" . mysqli_connect_errno();
            // echo "<br>";
            // echo "error de depuración: " . mysqli_connect_error();
            // exit;
        }
    }
}
