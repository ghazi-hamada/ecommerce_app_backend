<?php

include '../../connect.php';

$id = filterRequest("categoriesId");
$imageName = filterRequest("imageName");
deleteFile("../../uploads/categories", $imageName);
deleteData("categories", "categories_id = $id");
