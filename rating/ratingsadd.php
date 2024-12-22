<?php

include '../connect.php';

$usersid = filterRequest("usersid");
$itemsid = filterRequest("itemsid");
$rating = filterRequest("ratingValue");
$review = filterRequest("ratingreview");

$data = array(
    "rating_userid" => $usersid,
    "rating_itemid" => $itemsid,
    "rating_value" => $rating,
    "rating_review" => $review
);

insertData("rating",$data);