<?php

include '../../connect.php';

$orderid = filterRequest("orderid");
$userid = filterRequest("userid");

$data = array(
    "orders_status" => 1
);

updateData("orders", $data, "orders_id = $orderid AND orders_status = 0");

insertNotification(
    "order approved",
    "approved order Number: #$orderid",
    $userid,
    "users$userid",
    "none",
    "/pending",
);
