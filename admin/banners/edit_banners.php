<?php

include '../../connect.php';
$bannersId = filterRequest("bannersId"); //bannersId
$bannersTitle = filterRequest("bannersTitle"); //bannersTitle
$bannersTitleAr = filterRequest("bannersTitleAr"); //bannersTitleAr
$bannersDescription = filterRequest("bannersDescription"); //bannersDescription
$bannersDescriptionAr = filterRequest("bannersDescriptionAr"); //bannersDescriptionAr
$bannersStartDate = filterRequest("bannersStartDate"); //bannersStartDate
$bannersEndDate = filterRequest("bannersEndDate"); //bannersEndDate
$bannersStatus = filterRequest("bannersStatus"); //bannersStatus
$imagename = imageUpload( "../../upload/banners" , "files") ;
 
if ($imagename == 'empty') {
    $imagename = filterRequest("bannersImage");
} else {
    deleteFile("../../uploads/banners", filterRequest("bannersImage"));
}

$addData = array(
    "banners_title" => $bannersTitle,
    "banners_title_ar" => $bannersTitleAr,
    "banners_description" => $bannersDescription,
    "banners_description_ar" => $bannersDescriptionAr,
    "banners_image_url" => $imagename,
    "banners_start_date" => $bannersStartDate,
    "banners_end_date" => $bannersEndDate,
    "banners_status" => $bannersStatus,
);

updateData("banners", $addData, "banners_id = $bannersId");
