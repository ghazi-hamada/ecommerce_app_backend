<?php
include '../../connect.php';
$password = sha1($_POST["password"]);
$email = filterRequest("email");

$stmt = $con->prepare("SELECT * FROM delivery WHERE delivery_email = ? AND delivery_password = ?");
$stmt->execute(array($email, $password));
$count = $stmt->rowCount();

if ($count > 0) {
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(array("status" => "success", "data" => $data));
} else {
    echo json_encode(array("status" => "failure"));
}

?>