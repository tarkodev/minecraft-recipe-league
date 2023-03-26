<?php

class User {

    private $pdo;
    private $userId;

    public function __construct($userId){
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
        $this->userId = $userId;
    }
    function generateUserIfNotHereAndGetId() {
        $sql = "SELECT EXISTS(SELECT * FROM users WHERE id = :value)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['value' => $this->userId]);
        $exist = $stmt->fetchColumn();

        if($exist) {
            return $this->userId;
        } else {
            $sql = "INSERT INTO users(id, total) VALUES (:id, 0)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $this->userId]);
            return $this->pdo->lastInsertId();
        }
    }

    function getUserTotal() {
        $select_query = 'SELECT total FROM users WHERE id = :id';
        $stmt = $this->pdo->prepare($select_query);

        // éviter les injections SQL
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();

        // tableau associatif
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'];
    }

    function incrementUserTotal() {
        $update_query = "UPDATE users SET total = total + 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($update_query);

        // éviter les injections SQL
        $stmt->bindParam(':id', $this->userId, PDO::PARAM_INT);
        $stmt->execute();

        return $this->getUserTotal();
    }
}