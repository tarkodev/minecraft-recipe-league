<?php
include_once "server/php/items.php";


$_POST = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];

        // Vérification
        if (is_numeric($id)) {
            $id = intval($id);

            $return["minecraft_id"] = getMinecraftIdFromId($id);
            $return["texture_path"] = getTextureFromId($id);

            echo json_encode($return);
        } else {
            http_response_code(400);
            echo json_encode(array("error" => "Les données envoyées ne sont pas valides."));
        }
    }
}