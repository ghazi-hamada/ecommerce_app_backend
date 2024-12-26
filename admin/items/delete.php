<?php

include '../../connect.php';

$id = filterRequest("itemsId");
$imageName = filterRequest("imageName");
deleteFile("../../uploads/items", $imageName);
deleteData("items", "items_id = $id");
