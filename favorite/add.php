<?php

include '../connect.php';

$itemsid = filterRequest("itemsid");
$userid = filterRequest("usersid");


// code sql to insert data from favorite table
$stmt = $con->prepare("INSERT INTO favorite (favorite_itemsid, favorite_usersid) VALUES (?, ?)");
$stmt->execute([$itemsid, $userid]);
$count = $stmt->rowCount();

if ($count > 0) {
    echo json_encode(array("status" => "success"));
} else {
    echo json_encode(array("status" => "failure"));
}

?>
