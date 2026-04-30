<?php

class Database
{

    private $host = "localhost";
    private $dbname = "endure_db";
    private $username = "root";
    private $password = "";

    public function connect(): PDO
    {
        try {

            $conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                $this->username,
                $this->password
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $ex) {
            die("Connection has failed: " . $ex->getMessage());
        }
    }
}
