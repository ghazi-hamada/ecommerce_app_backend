<?php

include './connect.php';

$userid = filterRequest("userid");
try {

    getAllData("notification", "notification_userid = $userid ORDER BY notification_id DESC");
} catch (Exception $e) {
    echo $e->getMessage();
}
