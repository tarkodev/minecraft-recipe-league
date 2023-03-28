<?php
include_once "../class/Database.php";


$_POST = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $return["result"] = $database->getUsersTotal();
    echo json_encode($return);
}