<?php
require_once 'vendor/autoload.php';
define("MB", 1048576);

function filterRequest($requestname)
{
    return  htmlspecialchars(strip_tags($_POST[$requestname]));
}

function getAllData($table, $where = null, $values = null, $json = true)
{
    global $con;
    $data = array();
    if ($where == null) {
        $stmt = $con->prepare("SELECT  * FROM $table   ");
    } else {
        $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    }
    $stmt->execute($values);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure"));
        }
        return $count;
    } else {
        if ($count > 0) {
            return $data;
        } else {
            return json_encode(array("status" => "failure"));
        }
    }
}

function getData($table, $where = null, $values = null, $json = true)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("SELECT  * FROM $table WHERE   $where ");
    $stmt->execute($values);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    } else {

        return $count;
    }
}




function insertData($table, $data, $json = true)
{
    global $con;
    foreach ($data as $field => $v)
        $ins[] = ':' . $field;
    $ins = implode(',', $ins);
    $fields = implode(',', array_keys($data));
    $sql = "INSERT INTO $table ($fields) VALUES ($ins)";

    $stmt = $con->prepare($sql);
    foreach ($data as $f => $v) {
        $stmt->bindValue(':' . $f, $v);
    }
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}


function updateData($table, $data, $where, $json = true)
{
    global $con;
    $cols = array();
    $vals = array();

    foreach ($data as $key => $val) {
        $vals[] = "$val";
        $cols[] = "`$key` =  ? ";
    }
    $sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";

    $stmt = $con->prepare($sql);
    $stmt->execute($vals);
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}

function deleteData($table, $where, $json = true)
{
    global $con;
    $stmt = $con->prepare("DELETE FROM $table WHERE $where");
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
    return $count;
}

function imageUpload($imageRequest)
{
    global $msgError;
    $imagename  = rand(1000, 10000) . $_FILES[$imageRequest]['name'];
    $imagetmp   = $_FILES[$imageRequest]['tmp_name'];
    $imagesize  = $_FILES[$imageRequest]['size'];
    $allowExt   = array("jpg", "png", "gif", "mp3", "pdf");
    $strToArray = explode(".", $imagename);
    $ext        = end($strToArray);
    $ext        = strtolower($ext);

    if (!empty($imagename) && !in_array($ext, $allowExt)) {
        $msgError = "EXT";
    }
    if ($imagesize > 2 * MB) {
        $msgError = "size";
    }
    if (empty($msgError)) {
        move_uploaded_file($imagetmp,  "../upload/" . $imagename);
        return $imagename;
    } else {
        return "fail";
    }
}



function deleteFile($dir, $imagename)
{
    if (file_exists($dir . "/" . $imagename)) {
        unlink($dir . "/" . $imagename);
    }
}

function checkAuthenticate()
{
    if (isset($_SERVER['PHP_AUTH_USER'])  && isset($_SERVER['PHP_AUTH_PW'])) {
        if ($_SERVER['PHP_AUTH_USER'] != "wael" ||  $_SERVER['PHP_AUTH_PW'] != "wael12345") {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Page Not Found';
            exit;
        }
    } else {
        exit;
    }

    // End 
}


function   printFailure($message = "none")
{
    echo     json_encode(array("status" => "failure", "message" => $message));
}
function   printSuccess($message = "none")
{
    echo     json_encode(array("status" => "success", "message" => $message));
}

function result($count)
{
    if ($count > 0) {
        printSuccess();
    } else {
        printFailure();
    }
}

function sendEmail($to, $title, $body)
{
    // إعداد الهيدر لدعم HTML
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: support@appghazi.com" . "\r\n";
    $headers .= "CC: ghazihamada7@gmail.com" . "\r\n";

    // قالب HTML للرسالة
    $htmlBody = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email</title>
        <style>
            body {
                font-family: "Arial", sans-serif;
                background-color: #f9f9f9;
                margin: 0;
                padding: 0;
                color: #333;
            }
            .email-container {
                max-width: 600px;
                margin: 30px auto;
                background: #fff;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            .email-header {
                background: #FF4747;
                color: white;
                padding: 20px;
                text-align: center;
                font-size: 24px;
                font-weight: bold;
            }
            .email-body {
                padding: 30px;
                font-size: 16px;
                line-height: 1.8;
                color: #555;
            }
            .email-body p {
                margin: 0 0 15px;
            }
            .email-footer {
                background: #f1f1f1;
                text-align: center;
                padding: 15px;
                color: #888;
                font-size: 14px;
            }
            .email-footer a {
                color: #FF4747;
                text-decoration: none;
                font-weight: white;
            }

        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="email-header">
                ' . htmlspecialchars($title) . '
            </div>
            <div class="email-body">
                <p>' . nl2br($body) . '</p>
            </div>
            <div class="email-footer">
                &copy; 2024 App Ghazi | <a href="https://ghaziapp.store">ghaziapp.store</a>
            </div>
        </div>
    </body>
    </html>';

    // إرسال البريد
    mail($to, $title, $htmlBody, $headers);
}



/**
 * الحصول على Access Token من حساب الخدمة (Service Account) لاستخدامها في
 * مكتبة Google.
 *
 * @return string Access Token
 * @throws Exception في حالة وجود خطأ في الحصول على Access Token
 */
function getAccessToken()
{
    // بيانات حساب الخدمة (Service Account JSON)
    $serviceAccountJson = [
        "type" => "service_account",
        "project_id" => "e-commerce-a9af1",
        "private_key_id" => "24d85ec31146b38148f4d5d7e68b556c8bd10659",
        "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDAqOen4p/SNPrl\nGPr3gmZ/wJ8pl+XsZmQ4IMBzXvuGI8fkpBC2ahLHIdGA/s6QGbGMptY8ijtSSW4L\ncllM7rdHRxgK8iWRAzhi+eXz25XLhl1XKjoQc4zkRkC/SCMC1JgN6mxbXyTI0V7q\nQp5Focpm/+iGNgrhIQ8gm8g2GjohUGIfJrWxTI8lT1AJmy8rjwl7NTWlEplkkqov\n9qYKXC5oGX81dFcq3Y2mNk5pw7PO04ZltugF58vdSxEnqteueI7dxudGmX/gft83\nOgIjdoSksKH0D/zcBzB4biQQBwR7v2daNQaIt/+6Zy73uObjV68ar7f9geKEQyC7\nfDuoInClAgMBAAECggEAHVOYr72Xhm/6IRWEXljDMX8ouzQcE7giWUjjAqzarLiV\nnQA8awgKg7VqJrCi0NyYYkijI+QG06fJ0IQ/g65yx67SwhfKECDlXGudzVkHnEt/\nudz7qbVtJPJsuLFKYwvpeXTNubrp5oe4YwBxBzhxjuw1gPiI/2wA2X3dJB8p6hgG\nK3yqZ+paJgJFBrOyHDNb9JEyV5rXpPmBv4Ru9UBnQ1A/lMn9MGpY2U4JNY2E6b9T\nmw3jPFKZn9qkC8wNim1XSFET69Dw57y/nGWIvf6gOp0Jnqk5U4viouVjBmiAcjMn\nSaueREd03djcFDXfZY6KdkcUogIB/R2yQTag+EPKoQKBgQD8lp+2fyASBSUGQbJx\nYJUunW5FVYrfm3gygmodfBZgs3D5f+VrTazHsz1cLKAv1XEa9R+GKTyMZZYh28/A\ncE+b2F65YLJAMowextZke7wiXzfXirVNNZ6gfbD6Zp/x1iIP7/odP1p0jfpYZtVE\neymZDIZeelXbMllg711ZvzHwEwKBgQDDQxDNZckS+uTSRo5kSbTfYngiYasOKmHx\nL1cySiHC72JxdhUhy1eKhptErKUyfeUNw+bf2uB4rmkU56uiQ9WOfODIW53WjV3u\nZSvZgpYXt+PYRsrGuccLL7Qu5uZiOy49bJ5613yDcQ0fUJUeufB/c73oKbMCNcvK\n6T/g6W7jZwKBgQCCyj+zEMp7ajyMq9IJURiORaiGsE+zj15C0TK/R7SSp/fUyXvy\n/wp7cX1RUC419pCVg92p64pVrHh72cUOgVlHelc6KC7EfSf++7ih2UxGhAV3T3Sn\noR8dPVg5oJArelSy6fEZ2ectuSKrSbwetKOKg2jEXRY95SlQv0EYKp9iDwKBgAFl\nuHgP02mMXBjZImm7UU0L8lokosm+KedE9HXKrAUuG2E+fBY600yNfAz4w1HiLsJz\ndXKCqXqTnPLmWYcWvy9+20jzxuRjLJnpJXAwDAztZvcEQq07ZR5CYCK5ykpHrCQK\nY/PzGNQD+hkQRZDrubnfxSslKT/JzMSN+MQOR5nfAoGAbwbh8d0F/F+6BCmITE7h\nFW5ejwKnBXv8x6TLRurMbRXEpjY+i1cdLw0NdG8xeovC9UoDMfcIU0hD8+MfmMLA\nwMk632hf7Edc1xPJCNkaNMXjkKtLbkZfYytKJ43leEsOO0ipt2aRRdK+d8ThQCYa\n52Zqqn2lNz1ftibf9hLTdXY=\n-----END PRIVATE KEY-----\n",
        "client_email" => "firebase-adminsdk-tlaiq@e-commerce-a9af1.iam.gserviceaccount.com",
        "client_id" => "108631000510376998180",
        "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
        "token_uri" => "https://oauth2.googleapis.com/token",
        "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
        "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-tlaiq%40e-commerce-a9af1.iam.gserviceaccount.com",
        "universe_domain" => "googleapis.com"
    ];

    // نطاقات الوصول المطلوبة
    $scopes = [
        "https://www.googleapis.com/auth/userinfo.email",
        "https://www.googleapis.com/auth/firebase.database",
        "https://www.googleapis.com/auth/firebase.messaging"
    ];

    // إنشاء كائن حساب الخدمة باستخدام مكتبة Google
    $client = new Google_Client();
    $client->setAuthConfig($serviceAccountJson);
    $client->setScopes($scopes);

    // الحصول على Access Token
    $accessToken = $client->fetchAccessTokenWithAssertion();

    if (isset($accessToken['error'])) {
        throw new Exception('Error fetching access token: ' . $accessToken['error']);
    }

    return $accessToken['access_token'];
}




function sendGCM($title, $message, $topic, $pageid, $pagename)
{

    try {
        $url = 'https://fcm.googleapis.com/v1/projects/e-commerce-a9af1/messages:send';

        $fields = array(
            "message" => array(
                "topic" => $topic,
                "notification" => array(
                    "title" => $title,
                    "body" => $message
                ),
                "data" => array(
                    "pageid" => $pageid,
                    "pagename" => $pagename
                )
            )
        );


        $token = getAccessToken();
        $fields = json_encode($fields);
        $headers = array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        echo $result;
        return $result;
        curl_close($ch);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function insertNotification($title, $message, $userid, $topic, $pageid, $pagename)
{
    global $con;
    $stmt = $con->prepare("INSERT INTO `notification`( `notification_title`, `notification_body`, `notification_userid`) VALUES ('?','?','?')");
    $stmt->execute([$title, $message, $userid]);
    sendGCM($title, $message, $topic, $pageid, $pagename);
    $count = $stmt->rowCount();
    return $count;
}
