<?php
    session_start();

    $title = "Minecraft Recipes League";
    $author = "Gabriel MOURAD";

    $_SESSION["lang"] =  "en_us";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo $title; ?></title>
    <meta name="description" content="Projet de Programmation Web 2">
    <meta name="author" content="Par <?php echo $author; ?>">
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/minecraftia">
    <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/minecrafter-alt">
</head>

<body>
    <header>
        <p id="tooltip-text">Chargement...</p>
    </header>

    <section>
        <div id="explication">
            <h1 id="titre"><?php echo $title; ?></h1>

            <p id="description">
                Essaye de trouver les craft le plus rapidement possible!
            </p>
        </div>

        <div id="jeu">

            <form id="langForm">
                <label for=langEdit>Language: </label><input id = langEdit type="text" value="en_us" required>
                <button type="submit">Go</button>
            </form>

            <button id="start" type="button" onclick="start()">Démarrer</button>

            <br><br>

            <div id="grid_area">

                <div class="minecraft_grid" id="crafting_result"></div>
                <div class="minecraft_grid" id="crafting_grid"></div>

                <br>

                <div class="minecraft_grid" id="inventory_grid"></div>
            </div>
        </div>
    </section>

    <footer>
        Programmation Web 2 &mdash; 2023<br>Projet réalisé par Gabriel MOURAD
    </footer>

    <script src="script/event.js"></script>
</body>
</html>

