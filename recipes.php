<?php
include_once "backend/recipes.php";


$_POST = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        $return["id"] = $id === -1 ? getRandomRecipeId() : $id;
        $return["recipe"] = getIdRecipeMap()[$return["id"]];
        echo json_encode($return);
    }
}
