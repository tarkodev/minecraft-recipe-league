<?php
include_once "database/pdo.php";


class Database {

    private $pdo;

    public function __construct(){
        $this->pdo = getPdo();
    }

    function generateUserAndGetId() {
        $this->pdo->exec("INSERT INTO users(total) VALUES (0)");
        return $this->pdo->lastInsertId();
    }

    function getUsersSize() {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM users');
        return $stmt->fetchColumn();
    }

    function getUsersTotal() {
        $stmt = $this->pdo->query('SELECT SUM(total) FROM users');
        return $stmt->fetchColumn();
    }
}


