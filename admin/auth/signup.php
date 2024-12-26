<?php

include '../../connect.php';

$username = filterRequest("username");
$password = sha1($_POST["password"]);
$email = filterRequest("email");
$phone = filterRequest("phone");
$verifycode = rand(10000, 99999);

$stmt = $con->prepare("SELECT * FROM users WHERE users_email = ? OR users_phone = ?");
$stmt->execute(array($email, $phone));
$count = $stmt->rowCount();
if ($count > 0) {
    printFailure( "Email or Phone already exists");
} else {
    $data = array(
        "users_name" => $username,
        "users_email" => $email,
        "users_phone" => $phone,
        "users_password" => $password,
        "users_verifycode" => $verifycode,
        "users_type" => 2,
    );
    sendEmail($email, "Verify Code Ecommerce", "Your verify code is: $verifycode");
    $count = insertData("users", $data);
}
