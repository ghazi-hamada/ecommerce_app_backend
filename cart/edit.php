<?php

include '../connect.php';

$usersid = filterRequest("usersid");
$itemsid = filterRequest("itemsid");
$cartQuantity = filterRequest("cartQuantity");


$stmt = $con->prepare("UPDATE cart SET cart_quantity = $cartQuantity WHERE cart_usersid = $usersid AND cart_itemsid = $itemsid");
$stmt->execute();
$count = $stmt->rowCount();

if ($count > 0) {
    echo json_encode(array("status" => "success"));
} else {
    echo json_encode(array("status" => "failure"));
}