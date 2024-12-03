<?php

include '../connect.php';

$email = filterRequest("email");
$verifycode = rand(10000, 99999);
$stmt = $con->prepare("SELECT * FROM users WHERE users_email = '$email'");
$stmt->execute();
$count = $stmt->rowCount();
if ($count > 0) {
    $data = array(
        "users_verifycode" => $verifycode
    );
    updateData("users", $data, "users_email = '$email'", false);

    sendEmail($email, "Verify Code Ecommerce", "Your verify code is: $verifycode");
    echo json_encode(array("status" => "success"));
} else {
    echo json_encode(array("status" => "failure"));
}
