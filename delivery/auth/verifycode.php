<?php
include '../../connect.php';
$email = filterRequest("email");

$verify = filterRequest("verifycode");

$stmt = $con->prepare("SELECT * FROM delivery WHERE delivery_email = '$email' AND delivery_verifycode = '$verify'");

$stmt->execute();

$count = $stmt->rowCount();

if ($count > 0) {
    $data = array(
        "delivery_approve" => '1'
    );
    updateData("delivery", $data, "delivery_email = '$email'");
} else {
    printFailure("Verify code is wrong");
}
?>