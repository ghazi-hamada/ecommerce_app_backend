<?php

include '../../connect.php';

$username = filterRequest("username");
$password = sha1($_POST["password"]);
$email = filterRequest("email");
$phone = filterRequest("phone");
$verifycode = rand(10000, 99999);

$stmt = $con->prepare("SELECT * FROM delivery WHERE delivery_email = ? OR delivery_phone = ?");
$stmt->execute(array($email, $phone));
$count = $stmt->rowCount();
if ($count > 0) {
    printFailure( "Email or Phone already exists");
} else {
    $data = array(
        "delivery_name" => $username,
        "delivery_email" => $email,
        "delivery_phone" => $phone,
        "delivery_password" => $password,
        "delivery_verifycode" => $verifycode
    );
    sendEmail($email, "Verify Code Ecommerce", "Your verify code is: $verifycode");
    $count = insertData("delivery", $data);
}
