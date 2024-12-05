<?php

include '../connect.php';
$id = filterRequest("id");


getAllData("mycart", "cart_usersid = ? ", array($id));