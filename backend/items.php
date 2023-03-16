<?php

function getIdItemMap()
{
    // Read the JSON file
    $items_raw = file_get_contents('assets/items.json');

    // Decode the JSON file
    $items_raw_data = json_decode($items_raw, true);

    $items = [];
    foreach ($items_raw_data as $item_raw) {
        $items[$item_raw["id"]] = $item_raw["name"];
    }

    return $items;

}

function getMinecraftIdFromId($id)
{
    return getIdItemMap()[$id];
}

function getTextureFromId($id)
{
    if (file_exists("assets/textures/item/" . getMinecraftIdFromId($id) . ".png")) return "assets/textures/item/" . getMinecraftIdFromId($id) . ".png";
    return "https://minecraftitemids.com/item/128/" . getMinecraftIdFromId($id) . ".png";
    //return "assets/textures/item/" . getMinecraftIdFromId(405) . ".png";
}
