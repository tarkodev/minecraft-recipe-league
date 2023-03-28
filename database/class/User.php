<?php
include_once "pdo.php";

class User
{

    private $pdo;
    private $userId;

    public function __construct($userId) {
        $this->pdo = getPdo();
        $this->userId = $userId;
    }

    function generateUserIfNotHereAndGetId() {
        $sql = "SELECT EXISTS(SELECT * FROM users WHERE id = :value)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['value' => $this->userId]);
        $exist = $stmt->fetchColumn();

        if ($exist) {
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