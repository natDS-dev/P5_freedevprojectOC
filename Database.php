<?php
namespace App\Tools;
use PDO;
use PDOException;

class Database 
{
    private $pdo;
    private $dbName = "dbs522655";
    private $host = "db5000544407.hosting-data.io";
    private $user = "dbu513830";
    private $password = "3Flans.db";

    public function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbName . ';charset=utf8', $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getPDO() 
    {
        return $this->pdo;      
    } 
}