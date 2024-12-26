
<?php 

include '../../connect.php';

$msgError = array()  ;

$table = "categories";

$name = filterRequest("categoriesName");

$namear = filterRequest("categoriesNameAr"); 

$imagename = imageUpload( "../../upload/categories" , "files") ;

$data = array( 
"categories_name" => $name,
"categories_name_ar" => $namear,
"categories_image" => $imagename,
);

insertData($table , $data);