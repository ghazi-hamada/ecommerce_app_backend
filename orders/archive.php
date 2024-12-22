<?php

include '../connect.php';

$usersid = filterRequest("usersid");

getAllData('ordersView', "orders_usersid = '$usersid' AND orders_status = 4");