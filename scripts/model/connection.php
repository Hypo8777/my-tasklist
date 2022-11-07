<?php

session_start();

abstract class DBConnection
{
    private $hostname = "localhost";
    private $dbUsername = "root";
    protected $dbpassword = "";
    private $dbName = "task_db";

    // Construct our connection
    private function set_connection()
    {
        try {
            $localhost = $this->hostname;
            $username = $this->dbUsername;
            $password = $this->dbpassword;
            $database = $this->dbName;
            $dsn = 'mysql:host=' . $localhost . ';dbname=' . $database;
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            return $pdo;
        } catch (PDOException $th) {
            die('ERROR : ' . $th->getMessage() . "<br>");
        }
    }
    public function connect()
    {
        return $this->set_connection();
    }
}
