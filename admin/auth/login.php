<?php
include '../../connect.php';
$email = filterRequest("email");
$password = sha1($_POST["password"]);
$usersType = filterRequest("usersType");

$stmt = $con->prepare("SELECT * FROM users WHERE users_email = ? AND users_password = ? AND users_type = $usersType");
$stmt->execute(array($email, $password));
$count = $stmt->rowCount();

if ($count > 0) {
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(array("status" => "success", "data" => $data));
} else {
    echo json_encode(array("status" => "failure"));
}

?>