<?php
include '../connect.php';

$ordersid = filterRequest("ordersid");
try {
    
    deleteData("orders", "orders_id = $ordersid AND orders_status = 0");
} catch (Exception $e) {
    echo $e->getMessage();
}