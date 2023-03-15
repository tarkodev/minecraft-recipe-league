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
    <link href="https://fonts.cdnfonts.com/css/minecraftia" rel="stylesheet">
</head>

<body>
    <header>
        <form id="langForm">
            <input id = langEdit type="text" name="in" value="some data" />
            <button type="submit">Go</button>
        </form>
    </header>

    <section>
        <h1 id="titre"><?php echo $title; ?></h1>


        <p id="tooltip-text">Chargement...</p>


        <div class="crafting_result">
            <div class="minecraft_box" id="crafting_box_result"></div>
        </div>

        <div class="minecraft_grid" id="crafting_grid">

        </div>

        <div class="minecraft_grid" id="inventory_grid">

        </div>

        <?php

        $name = 'John Doe';

	?>

	<h1>Bonjo<?php echo $name; ?>uer </h1>
    </section>

    <footer>
        Programmation Web 2 &mdash; 2023<br>Projet fait par Gabriel MOURAD
    </footer>

    <script src="event.js"></script>
</body>
</html>

