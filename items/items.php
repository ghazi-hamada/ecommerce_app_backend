<?php
include "../connect.php";

$categoryid = filterRequest("id");
$userid = filterRequest("usersid");

if (empty($categoryid) || empty($userid)) {
    echo json_encode(array("status" => "failure", "message" => "Missing parameters"));
    exit();
}

$stmt = $con->prepare("
SELECT 
    items1view.*, 
    COALESCE(AVG(rating.rating_value), 0) AS rating,
    CASE 
        WHEN favorite.favorite_itemsid IS NOT NULL THEN 1 
        ELSE 0 
    END AS favorite
FROM items1view
LEFT JOIN favorite 
    ON favorite.favorite_itemsid = items1view.items_id 
    AND favorite.favorite_usersid = :userid
LEFT JOIN rating 
    ON rating.rating_itemid = items1view.items_id
WHERE categories_id = :categoryid
GROUP BY items1view.items_id
");

$stmt->bindParam(':categoryid', $categoryid, PDO::PARAM_INT);
$stmt->bindParam(':userid', $userid, PDO::PARAM_INT);

$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();

if ($count > 0) {
    echo json_encode(array("status" => "success", "data" => $data));
} else {
    echo json_encode(array("status" => "failure"));
}
?>
