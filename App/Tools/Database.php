<?php
namespace App\Tools;
use PDO;
use PDOException;

//Connexion bdd via interface PDO- DB connect through PDO interface
class Database 
{
    private $pdo;
    private $dbName = "P5";
    private $host = "localhost";
    private $user = "root";
    private $password = "";

    public function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbName . ';charset=utf8', $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getPDO() 
    {
        return $this->pdo;      
    } 
}