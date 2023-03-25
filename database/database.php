<?php

header("Content-Security-Policy: default-src 'self'");

$database_file = "database/database.sqlite";

$pdo = new PDO("sqlite:" . $database_file);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$create_table_query = "CREATE TABLE IF NOT EXISTS users (
        id     INTEGER NOT NULL PRIMARY KEY,
        total  INTEGER DEFAULT 0
    )";

$pdo->exec($create_table_query);

function generateUserAndGetId() {
    global $pdo;

    $pdo->exec("INSERT INTO users(total) VALUES (0)");
    return $pdo->lastInsertId();
}


function generateUserIfNotHereAndGetId($id) {
    global $pdo;

    $sql = "SELECT EXISTS(SELECT * FROM users WHERE id = :value)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['value' => $id]);
    $exist = $stmt->fetchColumn();

    if($exist) {
        return $id;
    } else {
        $sql = "INSERT INTO users(id, total) VALUES (:id, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $pdo->lastInsertId();
    }
}

function getUserTotal($id) {
    global $pdo;

    $select_query = 'SELECT total FROM users WHERE id = :id';
    $stmt = $pdo->prepare($select_query);

    // éviter les injections SQL
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // tableau associatif
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total'];
}

function incrementUserTotal($id) {
    global $pdo;

    $update_query = "UPDATE users SET total = total + 1 WHERE id = :id";
    $stmt = $pdo->prepare($update_query);

    // éviter les injections SQL
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return getUserTotal($id);
}

function getUsersSize() {
    global $pdo;

    $stmt = $pdo->query('SELECT COUNT(*) FROM users');
    return $stmt->fetchColumn();
}

function getUsersTotal() {
    global $pdo;

    $stmt = $pdo->query('SELECT SUM(total) FROM users');
    return $stmt->fetchColumn();
}

