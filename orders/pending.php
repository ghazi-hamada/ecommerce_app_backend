<?php

include '../connect.php';

$usersid = filterRequest("usersid");

getAllData('orders', "orders_usersid = '$usersid'");
