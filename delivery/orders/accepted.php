<?php

include "../../connect.php";

$orderdeliveryid = filterRequest("orderdeliveryid");

getAllData('ordersView', "orders_status = 3 AND orders_delivery = $orderdeliveryid");
