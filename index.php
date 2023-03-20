<?php
    session_start();

    $_SESSION["author"] = "Gabriel MOURAD";
    $_SESSION["title"] = "Minecraft Recipes League";
    $_SESSION["description"] = "Projet de Programmation Web 2";

    include_once "server/php/translations.php"
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo $_SESSION["title"]; ?></title>
    <meta name="description" content="<?php echo $_SESSION["description"]; ?>">
    <link rel="stylesheet" href="server/style/styles.css">
    <?php include_once "./server/html/head.php" ?>
</head>

<body>
    <header>
        <?php include_once "./server/html/header.php" ?>
    </header>

    <section>
        <div id="explication">
            <p id="description" class="translation"></p>
        </div>

        <div id="jeu">
            <form id="postForm">
                <button type="submit" class="translation" id="another"></button>
            </form>

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
        <?php include_once "./server/html/footer.php" ?>
    </footer>

    <script src="server/script/scripts.js"></script>
</body>
</html>

