<?php
include_once "database/database.php";


$_POST = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $return["result"] = getUsersTotal();
    echo json_encode($return);
}