<?php

//vu qu'il faut faire un "get" obligatoire

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET["easter"])) {
        $id = $_GET["easter"];
        echo $id == "egg" ? "Bravo, tu as trouvé un oeuf de pâques ^o^" : "Rip, tu es tombé sur l'oeuf d'Halloween ψ(｀∇´)ψ";
    }
}