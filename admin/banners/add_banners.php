<?php

include '../../connect.php';

$bannersTitle = filterRequest("bannersTitle"); //bannersTitle
$bannersTitleAr = filterRequest("bannersTitleAr"); //bannersTitleAr
$bannersDescription = filterRequest("bannersDescription"); //bannersDescription
$bannersDescriptionAr = filterRequest("bannersDescriptionAr"); //bannersDescriptionAr
  $bannersStartDate = filterRequest("bannersStartDate"); //bannersStartDate
$bannersEndDate = filterRequest("bannersEndDate"); //bannersEndDate
$bannersImage = imageUpload( "../../upload/banners" , "files") ;
$addData = array(
    "banners_title" => $bannersTitle,
    "banners_title_ar" => $bannersTitleAr,
    "banners_description" => $bannersDescription,
    "banners_description_ar" => $bannersDescriptionAr,
    "banners_image_url" => $bannersImage,
    "banners_start_date" => $bannersStartDate,
    "banners_end_date" => $bannersEndDate,
);

insertData("banners", $addData);


