<?php 

include "../../connect.php" ; 

$ordersid = filterRequest("id")  ;

getAllData("ordersDetailsView" , "cart_orders = $ordersid "); 

?>