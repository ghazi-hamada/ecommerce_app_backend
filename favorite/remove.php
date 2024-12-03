<?php

include '../connect.php';

$itemsid = filterRequest("itemsid");
$userid = filterRequest("usersid");

deleteData("favorite", "favorite_itemsid = $itemsid AND favorite_usersid = $userid");
?>