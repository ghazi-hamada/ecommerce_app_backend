<?php

include '../connect.php';

$cartOrders = filterRequest("cartOrders"); // قيمة الطلبات
$usersid = filterRequest("usersid");       // المستخدم الذي نتحقق منه

// جلب البيانات من ordersDetailsView
$stmt = $con->prepare("
    SELECT * 
    FROM ordersDetailsView 
    WHERE cart_orders = :cartOrders
");
$stmt->bindParam(':cartOrders', $cartOrders, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// إذا تم العثور على بيانات، التحقق من التقييم
if (!empty($data)) {
    foreach ($data as $key => $row) {
        $itemsid = $row['cart_itemsid']; // ID المنتج من ordersDetailsView

        // التحقق إذا قام المستخدم بتقييم هذا المنتج
        $ratingStmt = $con->prepare("
            SELECT 1 
            FROM rating 
            WHERE rating_userid = :usersid AND rating_itemid = :itemsid
        ");
        $ratingStmt->bindParam(':usersid', $usersid, PDO::PARAM_INT);
        $ratingStmt->bindParam(':itemsid', $itemsid, PDO::PARAM_INT);
        $ratingStmt->execute();

        // إضافة العمود hasRating لكل منتج
        $data[$key]['hasRating'] = $ratingStmt->rowCount() > 0 ? 1 : 0;
    }

    // إرسال النتيجة النهائية
    echo json_encode(array("status" => "success", "data" => $data));
} else {
    // إذا لم يتم العثور على بيانات
    echo json_encode(array("status" => "failure"));
}

?>
