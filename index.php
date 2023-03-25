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

    <main>

        <p class="translation" translation="description" id="description"></p>


        <div id="jeu">
            <form id="postForm">
                <button type="submit" class="translation" translation="another" id="another"></button>
            </form>

            <div id="grid_area">

                <div class="minecraft_grid" id="crafting_result"></div>
                <div class="minecraft_grid" id="crafting_grid"></div>

                <br>

                <div class="minecraft_grid" id="inventory_grid"></div>
            </div>
        </div>


    </main>

    <footer>
        <table>
            <tbody>
            <tr>
                <th scope="row" class="translation" translation="user_total"></th>
                <td id="user_total"></td>
            </tr>
            <tr>
                <th scope="row" class="translation" translation="users_total"></th>
                <td id="users_total"></td>
            </tr>
            <tr>
                <th scope="row" class="translation" translation="users_size"></th>
                <td id="users_size"></td>
            </tr>
            </tbody>
        </table>
        <?php include_once "./server/html/footer.php" ?>
    </footer>

    <script src="server/script/scripts.js"></script>
</body>
</html>

