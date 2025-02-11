<?php
 
 include '../../connect.php';

$bannersid = filterRequest("bannersid");
$imageName = filterRequest("imageName");

try {
    deleteFile("../../uploads/banners", $imageName);
    deleteData("banners", "banners_id  = $bannersid");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
