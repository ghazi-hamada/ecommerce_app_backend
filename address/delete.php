<?php

include "../connect.php";

$addressid = filterRequest("addressid");


try {
    deleteData("address", "address_id  = $addressid");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
