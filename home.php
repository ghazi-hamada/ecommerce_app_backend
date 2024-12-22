<?php
include "connect.php";
try {
    $AllData = array();
    
    $AllData["status"] = "success";
    $catigories = getAllData("categories", null, null, false);
    $AllData["categories"] = $catigories;
    
    $items = getAllData("itemstopselling", "1 = 1", null, false);
    $AllData["items"] = $catigories;
    
    
    $AllData["items"] = $items;
    
    echo json_encode($AllData);
} catch (Exception $e) {
    echo $e->getMessage();
}
