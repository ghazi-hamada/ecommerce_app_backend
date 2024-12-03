<?php

include '../connect.php';

$favoriteid = filterRequest("favoriteid"); 

deleteData("favorite", "favorite_id = $favoriteid ");
?>