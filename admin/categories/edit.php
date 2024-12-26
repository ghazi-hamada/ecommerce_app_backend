<?php

include '../../connect.php';
$categoriesId = filterRequest("categoriesId");
$categoriesName = filterRequest("categoriesName");
$categoriesNameAr = filterRequest("categoriesNameAr");
$categoriesImage = filterRequest("categoriesImage");

$res = imageUpload("../../uploads/categories", "files");
if ($res == 'empty') {

    $data = array(
        "categories_name" => $categoriesName,
        "categories_name_ar" => $categoriesNameAr,

    );
} else {
    deleteFile("../../uploads/categories", $categoriesImage);
    $data = array(
        "categories_name" => $categoriesName,
        "categories_name_ar" => $categoriesNameAr,
        "categories_image" => $res,

    );
}
updateData("categories", $data, "categories_id = $categoriesId");
