<?php

class Database {

    private $pdo;

    public function __construct(){
        header("Content-Security-Policy: default-src 'self'");

        $database_file = "database/database.sqlite";

        $this->pdo = new PDO("sqlite:" . $database_file);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->pdo->query('CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL
        );');

        $create_table_query = "CREATE TABLE IF NOT EXISTS users (
            id     INTEGER NOT NULL PRIMARY KEY,
            total  INTEGER DEFAULT 0
        )";

        $this->pdo->exec($create_table_query);
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


