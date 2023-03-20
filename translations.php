<?php
include_once "server/php/translations.php";


$_POST = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["id"]) && isset($_POST["lang"])) {
        $id = $_POST["id"];  $lang = $_POST["lang"];

        // Vérification de la qualité et de la quantité des données pour $id et $lang
        if(is_string($lang) && strlen($lang) <= 255) {
            if (is_numeric($id)) {
                $return["translation"] = getTranslationFromNumericId($id, $lang);
            } else if (is_string($id) && strlen($id) <= 255) {
                $return["translation"] = getTranslationFromStringId($id, $lang);
            } else {
                http_response_code(400);
                $return["translation"] = "error";
            }
        } else {
            http_response_code(400);
            $return["translation"] = "error";
        }
        echo json_encode($return);
    }
}