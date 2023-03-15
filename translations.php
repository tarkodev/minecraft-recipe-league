<?php
include_once "script/translations.php";


$_POST = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["id"]) && isset($_POST["lang"])) {
        $id = $_POST["id"];  $lang = $_POST["lang"];
        $return["translation"] = getTranslationFromId($id, $lang);
        echo json_encode($return);
    }
}