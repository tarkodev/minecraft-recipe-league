<!--
Ancien code accessible via un easter egg (CSS JS et HTML dans le même fichier pas bien d'après l'énoncé mais c'est que du bonus donc je le compacte)
-->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #3A3F3A;
            color: #FFFFFF;
        }

        .grid {
            display: inline-grid;
            grid-template-columns: repeat(3, 100px);
            grid-template-rows: repeat(3, 100px);
            grid-gap: 5px;
        }

        .cell {
            width: 100px;
            height: 100px;
            background-color: #7D9C3B;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2em;
            cursor: pointer;
            position: relative;
        }

        .cell.x::before, .cell.o::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            image-rendering: pixelated;
        }

        .cell.x::before {
            background-image: url("../../assets/minecraft/item/iron_sword.png"); /* Image de l'épée Minecraft */
        }

        .cell.o::before {
            background-image: url("../../assets/minecraft/item/iron_pickaxe.png"); /* Image de pioche Minecraft */
        }
    </style>
</head>
<body>
<div class="grid" id="grid"></div>

<script>
    const grid = document.getElementById("grid");
    const cells = [];
    let currentPlayer = "x";

    function checkForWinner() {
        const winConditions = [[0, 1, 2], [3, 4, 5], [6, 7, 8], [0, 3, 6], [1, 4, 7], [2, 5, 8], [0, 4, 8], [2, 4, 6]];
        for (const condition of winConditions) {
            const [a, b, c] = condition;
            if (cells[a].player && cells[a].player === cells[b].player && cells[a].player === cells[c].player) {
                return cells[a].player;
            }
        }
        return null;
    }

    function handleClick(event) {
        const cell = event.target;
        if (cell.player || checkForWinner()) {
            return;
        }

        cell.player = currentPlayer;
        cell.classList.add(currentPlayer);
        currentPlayer = currentPlayer === "x" ? "o" : "x";

        const winner = checkForWinner();
        if (winner) {
            alert(`gg!`);
        }
    }

    for (let i = 0; i < 9; i++) {
        const cell = document.createElement("div");
        cell.className = "cell";
        cell.addEventListener("click", handleClick);
        grid.appendChild(cell);
        cells.push(cell);
    }
</script>
</body>
</html>
