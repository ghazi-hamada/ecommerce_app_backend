<?php

include "../connect.php";

$usersid = filterRequest("usersid");
$addressid = filterRequest("addressid");
$orderstype = filterRequest("orderstype");
$pricedelivery = filterRequest("pricedelivery");
$ordersprice = filterRequest("ordersprice");
$couponid = filterRequest("couponid");
$orderPaymentMethod = filterRequest("orderPaymentMethod");
$couponDiscount = filterRequest("couponDiscount");

if ($orderstype == '1') {
    $pricedelivery = 0;
}

$totalPrice = $ordersprice + $pricedelivery;
// Check Coupon
$now = date("Y-m-d H:i:s");

$checkCoupon = getData("coupon", "coupon_id = '$couponid'  AND coupon_expiredate > '$now' AND coupon_count > 0 ", null, false);

if ($checkCoupon > 0) {
    $totalPrice = $totalPrice - $ordersprice *  $couponDiscount / 100;

    $stmt = $con->prepare("UPDATE coupon SET coupon_count = coupon_count - 1 WHERE coupon_id = $couponid");
    $stmt->execute();
    
}

$data = array(
    "orders_usersid"  =>  $usersid,
    "orders_address"  =>  $addressid,
    "orders_type"  =>  $orderstype,
    "orders_price_delivery"  =>  $pricedelivery,
    "orders_price"  =>  $ordersprice,
    "orders_coupon"  =>  $couponid,
    "orders_total_price"  =>  $totalPrice,
    "order_payment_method"  =>  $orderPaymentMethod,
);

$count = insertData("orders", $data, false);

if ($count > 0) {
    $stmt = $con->prepare("SELECT MAX(orders_id) FROM orders");
    $stmt->execute();
    $maxid = $stmt->fetchColumn();

    $data = array(
        "cart_orders"  =>  $maxid,
    );
    updateData("cart", $data, "cart_usersid = $usersid AND cart_orders = 0");
} else {
    echo json_encode(array("status" => "failure"));
}
