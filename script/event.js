let lang = "fr_fr";

// Load Crafting Grid
for (let i = 0; i < 9; i++) {
    addBox("crafting_grid", "crafting_box" + i);
}

// Load Inventory Grid
for (let i = 0; i < 18; i++) {
    addBox("inventory_grid", "inventory_box" + i);
}

addBox("crafting_result", "crafting_box_result");

let startForm = document.getElementById("startForm");

let langForm = document.getElementById("langForm");
langForm.addEventListener("submit", (e) => {
    e.preventDefault();

    lang = document.getElementById("langEdit").value;
});



// Pour simplifier mon usage des requêtes Ajax j'utilise 'fetch'
function ajaxRequest(routes, content, jsonCallback) {
    fetch(routes, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(content)
    }).then(response => {
        if (response.status === 200) response.json().then(jsonCallback);
    })
}

// Pour simplifier la traduction
function ajaxTranslation(id, jsonCallback) {
    ajaxRequest("translations.php", {
        "id": id,
        "lang": lang,
    }, jsonCallback)
}



function addBox(gridId, boxId) {
    const box = document.createElement('div');
    box.id = boxId;
    box.className = "minecraft_box";
    document.getElementById(gridId).appendChild(box);

    box.addEventListener("click", function (event) {
        if(!found()) {
            if (gridId === "crafting_grid") {
                let selectedBox = getSelectedBox();

                if (selectedBox != null) {
                    setBoxItem(this.id, selectedBox.firstElementChild.id);
                } else {
                    tooltip.style.display = 'none';
                    resetBox(this.id);
                    checkCrafting();
                }
            } else if (gridId === "inventory_grid") {
                selectBox(box.id);
            }
        }
    });

    // Info-bulle quand on mets la souris sur un objet comme dans Minecraft
    const tooltip = document.getElementById("tooltip-text");
    box.addEventListener('mouseover', () => {
        if(box.hasChildNodes()) {
            tooltip.style.display = 'block';

            ajaxTranslation(box.firstElementChild.id, json => {
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
    let boxs = document.getElementsByClassName("minecraft_box");
    for (let i = 0; i < boxs.length; i++) {
        resetBox(boxs[i].id);
    }
}





function setBoxItem(boxId, itemId) {
    ajaxRequest("items.php", {
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

function resetBox(boxId) {
    document.getElementById(boxId).innerHTML = ""
}

function selectBox(boxId) {
    if(document.getElementById(boxId).style.background === "gold") {
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
        if(child.style.background === "gold") return child;
    }
}



function updateInventory(recipeId, recipe) {
    let ingredientCache = [];

    if(recipe["ingredients"]) {
        ingredientCache = recipe["ingredients"]
    } else {
        let inshape = recipe["inShape"];
        for(let i in inshape) {
            let xxx = inshape[i];
            for(let j in xxx) {
                let x = xxx[j];
                if(x != null) ingredientCache.push(x);
            }
        }
    }

    let ingredient = ingredientCache.filter((x, i) => ingredientCache.indexOf(x) === i);

    for (let i = 0; i < ingredient.length; i++) {
        setBoxItem("inventory_box" + i, ingredient[i]);
    }
}



function start() {
    reset();
    ajaxRequest("recipes.php", {
        "id": -1
    }, json => {
        setBoxItem("crafting_box_result", json["id"]);
        updateInventory(json["id"], json["recipe"][0])
    })
}

function getCraftingAsTab() {
    let tab = [];
    for(let i = 0; i < 9; i++) {
        let id = "crafting_box" + i;
        if(document.getElementById(id).firstElementChild === null) {
            tab[i] = null;
        } else {
            tab[i] = parseInt(document.getElementById(id).firstElementChild.id);
        }
    }
    return tab;
}

function checkCrafting() {
    ajaxRequest("recipes.php", {
        "id": document.getElementById("crafting_box_result").firstElementChild.id,
    }, json => {
        let recipe = json["recipe"][0];

        let craft = getCraftingAsTab();
        let good = craft.filter(Number).length !== 0;

        console.log(craft);
        if(recipe["ingredients"]) {
            let ingredients = recipe["ingredients"];

            for (let i = 0; i < 9; i++) {
                for (let j = 0; j < ingredients.length; j++) {
                    if(craft[i] === ingredients[j]) {
                        ingredients.splice(j, 1);
                        craft[i] = null;
                    }
                }

                if(craft[i] != null) good = false;
            }
            if(ingredients.length !== 0) good = false;
        } else if(recipe["inShape"]) {
            let inShape = recipe["inShape"];

            let y_shape_length = inShape.length;
            let x_shape_length = inShape[0].length;

            let shapeCrafted = 0;
            for(let y_rightup = 0; y_rightup < 4-y_shape_length; y_rightup++) {
                for(let x_rightup = 0; x_rightup < 4-x_shape_length; x_rightup++) {
                    let isShapeValid = true;

                    for(let y_shape = 0; y_shape < y_shape_length; y_shape++) {
                        for(let x_shape = 0; x_shape < x_shape_length; x_shape++) {
                            if(craft[x_rightup+y_rightup*3+x_shape+y_shape*3] !== inShape[y_shape][x_shape]) {
                                isShapeValid = false;
                            }
                        }
                    }

                    if(isShapeValid) {
                        let canBeCrafted = true;
                        for(let i = 0; i < 9; i++) {
                            let craft_table_x = i % 3;
                            let craft_table_y = Math.floor(i / 3);
                            console.log(x_rightup + ":" + y_rightup);
                            console.log(craft_table_x + ":" + craft_table_y);
                            console.log(y_rightup <= craft_table_y <= (y_rightup+y_shape_length-1));
                            if(!((y_rightup <= craft_table_y && craft_table_y < (y_rightup+y_shape_length)) && (x_rightup <= craft_table_x && craft_table_x < (x_rightup+x_shape_length)))) {
                                console.log(i);
                                if(craft[i] != null) canBeCrafted = false;
                            }
                        }
                        if(canBeCrafted) shapeCrafted++;
                    }
                }
            }

            if(shapeCrafted !== 1) good = false;
        }

        if(good) document.getElementById("crafting_box_result").firstElementChild.style.background = "green";
    })


}

function found() {
    return (document.getElementById("crafting_box_result").firstElementChild.style.background === "green");
}

