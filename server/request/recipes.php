<?php
include_once "../php/recipes.php";


$_POST = json_decode(file_get_contents("php://input"), true);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];

        // Vérification
        if (is_numeric($id)) {
            $id = intval($id);

            $return["id"] = $id === -1 ? getRandomRecipeId() : $id;
            $return["recipe"] = getIdRecipeMap()[$return["id"]];

            echo json_encode($return);
        } else {
            http_response_code(400);
            echo json_encode(array("error" => "Les données envoyées ne sont pas valides."));
        }
    }
}
