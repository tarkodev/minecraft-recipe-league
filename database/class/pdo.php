<?php

function getPdo() {
    header("Content-Security-Policy: default-src 'self'");

    $database_file = "../database.sqlite";

    $pdo = new PDO("sqlite:" . $database_file);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $create_table_query = "CREATE TABLE IF NOT EXISTS users (
            id     INTEGER NOT NULL PRIMARY KEY,
            total  INTEGER DEFAULT 0
        )";

    $pdo->exec($create_table_query);

    return $pdo;
}