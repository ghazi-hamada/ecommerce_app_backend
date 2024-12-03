<?php
include "connect.php";
$AllData = array();

$AllData["status"] = "success";
$catigories = getAllData("categories", null, null, false);
$AllData["categories"] = $catigories;

$items = getAllData("items1view", "items_discount > 0", null, false);
$AllData["items"] = $catigories;


$AllData["items"] = $items;

echo json_encode($AllData);
