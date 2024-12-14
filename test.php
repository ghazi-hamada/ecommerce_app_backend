<?php
include 'connect.php';
//DEVICE_TOKEN
// $token = filterRequest("deviceToken");
// $accessToken = filterRequest("accessToken");
// $title = filterRequest("title");
// $body = filterRequest("body");

// sendGCM($title,
// $body,
// $accessToken,
// $token);

sendGCM("title", "body", "users", "", "");
