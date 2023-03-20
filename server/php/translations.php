<?php

include_once "server/php/items.php";
function getTranslations($lang)
{
    // Read the JSON file
    $translations_raw = file_get_contents('assets/lang/' . $lang . '.json');

    // Decode the JSON file
    return json_decode($translations_raw, true);
}

function getTranslationFromNumericId($id, $lang)
{
    if (array_key_exists("item.minecraft." . getMinecraftIdFromId($id), getTranslations($lang))) return getTranslations($lang)["item.minecraft." . getMinecraftIdFromId($id)];
    if (array_key_exists("block.minecraft." . getMinecraftIdFromId($id), getTranslations($lang))) return getTranslations($lang)["block.minecraft." . getMinecraftIdFromId($id)];
    return "invalid_numeric_id";
}

function getTranslationFromStringId($id, $lang)
{
    return array_key_exists($id, getTranslations($lang))
        ? getTranslations($lang)[$id]
        : "invalid_id";
}


