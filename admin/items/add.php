
<?php 

include '../../connect.php';

$msgError = array()  ;

$table = "items";

$name = filterRequest("itemsName");

$namear = filterRequest("itemsNameAr"); 

$itemsDescription = filterRequest("itemsDescription");

$itemsDescriptionAr = filterRequest("itemsDescriptionAr");

$itemsPrice = filterRequest("itemsPrice");

$itemsDiscount = filterRequest("itemsDiscount");

$itemsCategory = filterRequest("itemsCategory");

$itemsCount = filterRequest("itemsCount");

$itemsActive = filterRequest("itemsActive");


$imagename = imageUpload( "../../upload/items" , "files") ;

$data = array( 
"items_name" => $name,
"items_name_ar" => $namear,
"items_desc" => $itemsDescription,
"items_desc_ar" => $itemsDescriptionAr,
"items_price" => $itemsPrice,
"items_discount" => $itemsDiscount,
"items_cat" => $itemsCategory,
"items_count" => $itemsCount,
"items_image" => $imagename,
"items_active" => $itemsActive,
);

insertData($table , $data);