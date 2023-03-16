<?php

function getIdRecipeMap()
{
    // Read the JSON file
    $recipes_raw = file_get_contents('assets/recipes.json');

    // Decode the JSON file
    return json_decode($recipes_raw, true);
}

function getRandomRecipeId()
{
    $recipes_keys = array_keys(getIdRecipeMap());

    $recipe_count = count($recipes_keys);
    $recipe_random_index = rand(0, $recipe_count);

    return $recipes_keys[$recipe_random_index];
}