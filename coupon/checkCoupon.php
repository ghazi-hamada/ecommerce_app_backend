<?php

include "../connect.php";

$coupon = filterRequest("coupon");

$now = date("Y-m-d H:i:s");

getData("coupon", "coupon_name = '$coupon'  AND coupon_expiredate >= '$now' AND coupon_count > 0 ");

//C:\xampp\htdocs\ecommerce_app_backend\coupon\checkCoupon.php