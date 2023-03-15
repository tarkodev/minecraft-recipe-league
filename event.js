
const box = document.querySelectorAll(".minecraft_box");


let lang = "fr_fr";

let langForm = document.getElementById("langForm");
langForm.addEventListener("submit", (e) => {
    e.preventDefault();

    lang = document.getElementById("langEdit").value;
});

function setBoxEvent(boxId) {
    const target = document.getElementById(boxId);
    const tooltip = document.getElementById("tooltip-text");

    // change display to 'block' on mouseover
    target.addEventListener('mouseover', () => {
        tooltip.style.display = 'block';

        const request = new XMLHttpRequest();

        const requestData = {
            "id": target.firstElementChild.id,
            "lang": lang
        }

        request.open("POST", "translations.php", true);
        request.onreadystatechange = function() {
            if (request.status === 200 && request.readyState === 4) {
                let response = JSON.parse(request.responseText);
                tooltip.innerHTML = response["translation"];
            }
        };

        request.setRequestHeader("Accept", "application/json");
        request.setRequestHeader("Content-Type", "application/json");

        request.send(JSON.stringify(requestData));
    }, false);

    // change display to 'none' on mouseleave
    target.addEventListener('mouseleave', () => {
        tooltip.style.display = 'none';
    }, false);

    target.addEventListener('mousemove', (event) => {
        tooltip.style.top = (event.pageY - 30) + 'px';
        tooltip.style.left = (event.pageX + 20) + 'px';
    }, false);
}

function setBoxItem(boxId, itemId) {
    const request = new XMLHttpRequest();

    const requestData = {
        "id": itemId,
    }

    request.open("POST", "items.php", true);
    request.onreadystatechange = function() {
        if (request.status === 200 && request.readyState === 4) {
            let response = JSON.parse(request.responseText);
            let box = document.getElementById(boxId);
            box.innerHTML = "<img id="+ itemId +" alt=" + response["minecraft_id"] + " src=" + response["texture_path"] + " >";
            setBoxEvent(boxId);
            checkCrafting();
        }
    };

    request.setRequestHeader("Accept", "application/json");
    request.setRequestHeader("Content-Type", "application/json");

    request.send(JSON.stringify(requestData));
}

function resetBox(boxId) {
    let old_element = document.getElementById(boxId);
    let new_element = old_element.cloneNode();
    old_element.parentNode.replaceChild(new_element, old_element);
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
    return null;
}

// Load Crafting Grid
for (let i = 0; i < 9; i++) {
    const crafting_grid = document.getElementById("crafting_grid");
    crafting_grid.innerHTML += '<div class="minecraft_box" id="crafting_box' + i + '"></div>';
}

for (let child of document.getElementById("crafting_grid").children) {
    child.addEventListener("click", function (event) {
        let selectedBox = getSelectedBox();
        //selectedBox.initEvent('mouseleave', true, true);
        resetBox(child.id);
        if(selectedBox != null) {
            setBoxItem(child.id, selectedBox.firstElementChild.id);
        }
    })
}

// Load Inventory Grid
for (let i = 0; i < 18; i++) {
    const crafting_grid = document.getElementById("inventory_grid");
    let id = "inventory_box" + i;
    crafting_grid.innerHTML += '<div class="minecraft_box" id="' + id + '"></div>';

}

for (let child of document.getElementById("inventory_grid").children) {
    child.addEventListener("click", function (event) {
        selectBox(child.id);
    })
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

function updateRecipe() {
    const request = new XMLHttpRequest();

    const requestData = {
        "id": -1,
    }

    request.open("POST", "recipes.php", true);
    request.onreadystatechange = function() {
        if (request.status === 200 && request.readyState === 4) {
            let response = JSON.parse(request.responseText);
            setBoxItem("crafting_box_result", response["id"]);
            updateInventory(response["id"], response["recipe"][0])
        }
    };

    request.setRequestHeader("Accept", "application/json");
    request.setRequestHeader("Content-Type", "application/json");

    request.send(JSON.stringify(requestData));
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
    const request = new XMLHttpRequest();

    const requestData = {
        "id": document.getElementById("crafting_box_result").firstElementChild.id,
    }

    request.open("POST", "recipes.php", true);
    request.onreadystatechange = function() {
        if (request.status === 200 && request.readyState === 4) {
            let response = JSON.parse(request.responseText);
            let recipe = response["recipe"][0];

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
                        let shapeIsCrafted = true;

                        for(let y_shape = 0; y_shape < y_shape_length; y_shape++) {
                            for(let x_shape = 0; x_shape < x_shape_length; x_shape++) {
                                if(craft[x_rightup+y_rightup*3+x_shape+y_shape*3] !== inShape[y_shape][x_shape]) {
                                    shapeIsCrafted = false;
                                }
                            }
                        }

                        if(shapeIsCrafted) shapeCrafted++;
                    }
                }

                if(shapeCrafted !== 1) good = false;
            }

            if(good) document.getElementById("crafting_box_result").firstElementChild.style.background = "green";
        }
    };

    request.setRequestHeader("Accept", "application/json");
    request.setRequestHeader("Content-Type", "application/json");

    request.send(JSON.stringify(requestData));
}


/*setBoxItem("crafting_box4", 925);
setBoxItem("crafting_box0", 926);
setBoxItem("crafting_box5", 927);
setBoxItem("crafting_box6", 928);
setBoxItem("crafting_box8", 929);

//selectBox("inventory_box1");


setBoxItem("crafting_box2", 1);*/

updateRecipe();


/*box.forEach(function(button) {
    button.addEventListener("click", function() {
        const script = new XMLHttpRequest();

        script.open("GET", "minecraft_id.php?id=", true);
        script.onreadystatechange = function() {
            if (script.status === 200 && script.readyState === 4) {
                // handle the response from the PHP script
                console.log(this.responseText);
            } else {
                throw new Error("Bad Request");
            }
        };

        script.send();
    });
});*/

/*var xhr = new XMLHttpRequest();
xhr.open("POST", "minecraft_id.php", true);
xhr.setRequestHeader('Content-Type', 'application/json');
xhr.onreadystatechange = function() {
    if (xhr.status === 200 && xhr.readyState === 4) {
        // handle the response from the PHP script
        alert(xhr.responseText);
    } else {
        throw new Error("Bad Request" + xhr.status + ":" + xhr.readyState);
    }
};
xhr.send(JSON.stringify({
    value: "value"
}));*/
/*fetch("minecraft_id.php", {
    method: "POST",
    headers: {
        "Accept": "application/json",
        "Content-Type": "application/json"
    },
    body: {
        "type": "name",
        "id": "1"
    }
}).then(function(body){
    return body.text(); // <--- THIS PART WAS MISSING
}).then(function(data) {
    console.log(data);
});*/