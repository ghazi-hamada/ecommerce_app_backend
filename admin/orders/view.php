<?php

include '../connect.php';

$usersid = filterRequest("usersid");

getAllData('ordersView', "1 = 1 AND orders_status != 4");
