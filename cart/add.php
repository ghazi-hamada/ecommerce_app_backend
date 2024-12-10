<?php

include '../connect.php';

$usersid = filterRequest("usersid");
$itemsid = filterRequest("itemsid");
$cartQuantity = filterRequest("cartQuantity");



$stmt = $con->prepare("INSERT INTO cart (cart_usersid, cart_itemsid, cart_quantity, cart_orders) VALUES ($usersid, $itemsid, $cartQuantity, 0)");
$stmt->execute();
$count = $stmt->rowCount();

if ($count > 0) {
    echo json_encode(array("status" => "success"));
} else {
    echo json_encode(array("status" => "failure"));
}
