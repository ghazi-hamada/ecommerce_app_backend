<?php 
include '../../connect.php';

 
getAllData('ordersView', "orders_status IN (1, 2, 3)");
 