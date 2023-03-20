<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Morpion Minecraft</title>
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
            background-image: url("assets/textures/item/iron_sword.png"); /* Image de l'épée Minecraft */
        }
        .cell.o::before {
            background-image: url("assets/textures/item/iron_pickaxe.png"); /* Image de pioche Minecraft */
        }
    </style>
</head>
<body>
    <h1>Morpion Minecraft</h1>
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
		    const winnerName = winner === 'x' ? "Épée" : "Pioche";
                alert(`Le joueur ${winnerName} a gagné !`);
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