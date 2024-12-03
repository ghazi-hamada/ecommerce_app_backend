<?php

include '../connect.php';

$email = filterRequest("email");

$verifycode = rand(10000, 99999);

$data = array(
    "users_verifycode" => $verifycode
);

sendEmail($email, "Verify Code Ecommerce", "Your verify code is: $verifycode");

updateData("users", $data, "users_email = '$email'");

?>