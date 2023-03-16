<?php
include_once "backend/items.php";


$_POST = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        $return["minecraft_id"] = getMinecraftIdFromId($id);
        $return["texture_path"] = getTextureFromId($id);
        echo json_encode($return);
    }
}