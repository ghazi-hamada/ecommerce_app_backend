<?php

include '../connect.php';

$usersid = filterRequest("usersid");
$itemsid = filterRequest("itemsid");
$cartQuantity = filterRequest("cartQuantity");



$stmt = $con->prepare("INSERT INTO cart (cart_usersid, cart_itemsid, cart_quantity) VALUES ($usersid, $itemsid, $cartQuantity)");
$stmt->execute();
$count = $stmt->rowCount();

if ($count > 0) {
    echo json_encode(array("status" => "success"));
} else {
    echo json_encode(array("status" => "failure"));
}

?>
