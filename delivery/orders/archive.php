<?php

include '../connect.php';

$orderdeliveryid = filterRequest("orderdeliveryid");

getAllData('ordersView', "1 = 1 AND orders_status = 4 AND orders_delivery = $orderdeliveryid");
