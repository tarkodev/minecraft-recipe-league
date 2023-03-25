function getCookie(cookieName) {
    return document.cookie
        .split("; ")
        .find((row) => row.startsWith(cookieName + "="))
        ?.split("=")[1];
}

function setCookieLanguage(value) {
    document.cookie = "lang=" + value;
}
if(getCookie("lang") === undefined) {
    setCookieLanguage("fr_fr");
}
let lang = getCookie("lang");

async function updateStats() {
    await ajaxRequest("user_total_get.php", {"id":getCookie("userId")}, json => {
        document.getElementById("user_total").innerHTML = json["total"];
    })
    await ajaxRequest("users_total_get.php", {}, json => {
        document.getElementById("users_total").innerHTML = json["result"];
    })
    await ajaxRequest("users_size_get.php", {}, json => {
        document.getElementById("users_size").innerHTML = json["result"];
    })
}

async function updateUsers() {
    if(getCookie("userId") === undefined) {
        await ajaxRequest("database_users.php", {}, json => {
            document.cookie = "userId=" + json["id"];
            updateStats();
        });
    } else {
        await ajaxRequest("database_users.php", {"id":getCookie("userId")}, json => {
            updateStats();
        });
    }
}

// Load Crafting Grid
for (let i = 0; i < 9; i++) {
    addBox("crafting_grid", "crafting_box" + i);
}

// Load Inventory Grid
for (let i = 0; i < 18; i++) {
    addBox("inventory_grid", "inventory_box" + i);
}

addBox("crafting_result", "crafting_box_result");

//For Language
const getForm = document.getElementById("getForm");

getForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    lang = getForm.querySelector("select[name='lang']").value;
    setCookieLanguage(lang);
    await translateAll();
})

getForm.querySelector("select[name='lang']").value = getCookie("lang");

// For Starting the Game
const postForm = document.getElementById("postForm")
postForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    await start();
});

window.addEventListener("load", async () => {
    updateUsers().then(() => translateAll().then(() => start()));
});

async function translateAll() {
    for (let toTranslate of document.getElementsByClassName("translation")) {

        await ajaxTranslation(toTranslate.getAttribute("translation"), (json) => {
            toTranslate.innerHTML = json["translation"];
        });
    }
}

// Pour simplifier mon usage des requêtes Ajax j'utilise 'fetch'
async function ajaxRequest(routes, content, jsonCallback) {
    await fetch(routes, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(content)
    }).then(response => {
        if (response.ok) response.json().then(jsonCallback);
    })
}

// Pour simplifier la traduction
async function ajaxTranslation(id, jsonCallback) {
    await ajaxRequest("translations.php", {
        "id": id,
        "lang": lang,
    }, jsonCallback)
}

function addBox(gridId, boxId) {
    const box = document.createElement('div');
    box.id = boxId;
    box.className = "minecraft_box";
    document.getElementById(gridId).appendChild(box);

    box.addEventListener("click", async function (event) {
        if (!found()) {
            if (gridId === "crafting_grid") {
                let selectedBox = getSelectedBox();

                if (selectedBox != null) {
                    await setBoxItem(this.id, selectedBox.firstElementChild.id);
                } else {
                    tooltip.style.display = 'none';
                    resetBox(this.id);
                    await checkCrafting();
                }
            } else if (gridId === "inventory_grid") {
                selectBox(box.id);
            }
        }
    });

    // Info-bulle quand on mets la souris sur un objet comme dans Minecraft
    const tooltip = document.getElementById("tooltip-text");
    box.addEventListener('mouseover', async () => {
        if (box.hasChildNodes()) {
            tooltip.style.display = 'block';

            await ajaxTranslation(parseInt(box.firstElementChild.id), json => {
                tooltip.innerHTML = json["translation"];
            });
        }
    }, false);

    box.addEventListener('mouseleave', () => {
        tooltip.style.display = 'none';
    }, false);

    box.addEventListener('mousemove', (event) => {
        tooltip.style.top = (event.pageY - 30) + 'px';
        tooltip.style.left = (event.pageX + 20) + 'px';
    }, false);
}

function reset() {
    let selected = getSelectedBox()
    if (selected != null) selected.style.background = "";

    let boxs = document.getElementsByClassName("minecraft_box");
    for (let i = 0; i < boxs.length; i++) {
        resetBox(boxs[i].id);
    }
}


async function setBoxItem(boxId, itemId) {
    itemId = parseInt(itemId);
    await ajaxRequest("items.php", {
        "id": itemId,
    }, json => {
        let box = document.getElementById(boxId);
        let img = document.createElement('img');
        img.id = itemId;
        img.alt = json["minecraft_id"];
        img.src = json["texture_path"];
        box.innerHTML = "";
        box.appendChild(img);

        checkCrafting();
    })
}

function found() {
    return (document.getElementById("crafting_box_result").firstElementChild.style.background === "green");
}


function resetBox(boxId) {
    document.getElementById(boxId).innerHTML = ""
}

function selectBox(boxId) {
    if (document.getElementById(boxId).style.background === "gold") {
        document.getElementById(boxId).style.background = "";
    } else {
        for (let child of document.getElementById("inventory_grid").children) {
            child.style.background = "";
        }

        document.getElementById(boxId).style.background = "gold";
    }
}

function getSelectedBox() {
    for (let child of document.getElementById("inventory_grid").children) {
        if (child.style.background === "gold") return child;
    }
}

function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}


function getRandomItemId() {
    let itemId = getRandomInt(1100.) + 1;

    return itemId;
}

function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}

async function updateInventory(recipeId, recipes) {
    let ingredientCache = [];

    for (let r = 0; r < recipes.length; r++) {
        let recipe = recipes[r];
        if (recipe["ingredients"]) {
            ingredientCache.push.apply(ingredientCache, recipe["ingredients"]);
        } else {
            let inshape = recipe["inShape"];
            for (let i in inshape) {
                let xxx = inshape[i];
                for (let j in xxx) {
                    let x = xxx[j];
                    if (x != null) ingredientCache.push(x);
                }
            }
        }
    }


    let ingredient = ingredientCache.filter((x, i) => ingredientCache.indexOf(x) === i);

    while (ingredient.length < 18) {
        ingredient.push(getRandomItemId());
    }

    shuffleArray(ingredient);

    for (let i = 0; i < 18; i++) {
        await setBoxItem("inventory_box" + i, ingredient[i]);
    }

}


async function start() {
    reset();
    await ajaxRequest("recipes.php", {
        "id": -1
    }, json => {
        setBoxItem("crafting_box_result", json["id"]);
        updateInventory(json["id"], json["recipe"])
    })
}

function getCraftingAsTab() {
    let tab = [];
    for (let i = 0; i < 9; i++) {
        let id = "crafting_box" + i;
        if (document.getElementById(id).firstElementChild === null) {
            tab[i] = null;
        } else {
            tab[i] = parseInt(document.getElementById(id).firstElementChild.id);
        }
    }
    return tab;
}

async function checkCrafting() {
    await ajaxRequest("recipes.php", {
        "id": parseInt(document.getElementById("crafting_box_result").firstElementChild.id),
    }, json => {
        let recipes = json["recipe"];

        for (let i = 0; i < recipes.length; i++) {
            let recipe = recipes[i];

            let craft = getCraftingAsTab();
            let good = craft.filter(Number).length !== 0;

            if (recipe["ingredients"]) {
                let ingredients = recipe["ingredients"];

                for (let i = 0; i < 9; i++) {
                    for (let j = 0; j < ingredients.length; j++) {
                        if (craft[i] === ingredients[j]) {
                            ingredients.splice(j, 1);
                            craft[i] = null;
                        }
                    }

                    if (craft[i] != null) good = false;
                }
                if (ingredients.length !== 0) good = false;
            } else if (recipe["inShape"]) {
                let inShape = recipe["inShape"];

                let y_shape_length = inShape.length;
                let x_shape_length = inShape[0].length;

                let shapeCrafted = 0;
                for (let y_rightup = 0; y_rightup < 4 - y_shape_length; y_rightup++) {
                    for (let x_rightup = 0; x_rightup < 4 - x_shape_length; x_rightup++) {
                        let isShapeValid = true;

                        for (let y_shape = 0; y_shape < y_shape_length; y_shape++) {
                            for (let x_shape = 0; x_shape < x_shape_length; x_shape++) {
                                if (craft[x_rightup + y_rightup * 3 + x_shape + y_shape * 3] !== inShape[y_shape][x_shape]) {
                                    isShapeValid = false;
                                }
                            }
                        }

                        if (isShapeValid) {
                            let canBeCrafted = true;
                            for (let i = 0; i < 9; i++) {
                                let craft_table_x = i % 3;
                                let craft_table_y = Math.floor(i / 3);
                                if (!((y_rightup <= craft_table_y && craft_table_y < (y_rightup + y_shape_length)) && (x_rightup <= craft_table_x && craft_table_x < (x_rightup + x_shape_length)))) {
                                    if (craft[i] != null) canBeCrafted = false;
                                }
                            }
                            if (canBeCrafted) shapeCrafted++;
                        }
                    }
                }

                if (shapeCrafted !== 1) good = false;
            }

            if (good) {
                ajaxRequest("user_total_add.php", {"id":getCookie("userId")}, json => {
                    updateStats();
                });
                document.getElementById("crafting_box_result").firstElementChild.style.background = "green";
            }
        }
    })
}

