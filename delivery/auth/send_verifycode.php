<?php

include '../../connect.php';

$email = filterRequest("email");

$verifycode = rand(10000, 99999);

$data = array(
    "delivery_verifycode" => $verifycode
);

sendEmail($email, "Verify Code Ecommerce", "Your verify code is: $verifycode");

updateData("delivery", $data, "delivery_email = '$email'");

?>