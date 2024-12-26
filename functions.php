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

    try {
        // إعداد الاستعلام بناءً على وجود شرط WHERE
        if ($where == null) {
            $stmt = $con->prepare("SELECT * FROM $table");
        } else {
            $stmt = $con->prepare("SELECT * FROM $table WHERE $where");
        }

        // تنفيذ الاستعلام
        $stmt->execute($values);

        // جلب البيانات
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();

        // التعامل مع النتيجة بناءً على التنسيق المطلوب
        if ($json == true) {
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            return array("status" => "success", "data" => $data);
        }
    } catch (Exception $e) {
        // التعامل مع الأخطاء
        if ($json == true) {
            echo json_encode(array("status" => "failure", "message" => $e->getMessage()));
        } else {
            return array("status" => "failure", "message" => $e->getMessage());
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

function imageUpload($dir, $imageRequest)
{
    global $msgError;
    if (isset($_FILES[$imageRequest])) {
        $imagename  = rand(1000, 10000) . $_FILES[$imageRequest]['name'];
        $imagetmp   = $_FILES[$imageRequest]['tmp_name'];
        $imagesize  = $_FILES[$imageRequest]['size'];
        $allowExt   = array("jpg", "png", "gif", "mp3", "pdf" , "svg",'webp');
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
            move_uploaded_file($imagetmp,  $dir . "/" . $imagename);
            return $imagename;
        } else {
            return $msgError;
        }
    }else {
        return 'empty' ; 
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
        "private_key_id" => "109be236b00af2f969d2e6a67f95a70a7ff8fc25",
        "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCdFWTMI3CGsj2l\nVYgj/QEgtmPniVM0MytpCN/2j77JKxyXT8okgAJR852QklbNIAONZMOXvfXCilSQ\nGy4uY53bvQhs4MZYLu/p5yWZ3bo+AoY+damItptGezAVe9LOdHBEFJqsovFH7x/w\n8i0Ln54R2M6UqIBdBBdbYn0yq6ZETHK2QTskYL8hsmg5DsBEerXmK1BkbQOPojuY\n2ATiVNqRN2ToNbYja12pseWa5leX2AQx3HTUwhS5sJhLmTtu4Ktf4YcBpV3Adh04\nj472pDzFZzxCttWt4egDEvK79XFv7eEpFVWELWjd4gsQ5578oko4asNUKVopYjXk\noGHcRwABAgMBAAECggEAAgQJRBlcU04sPlfnpFaV0h6lD46Sk8PoVbO4dAiKMTit\naD+s32UJIpYWuok2NfGoOgNGA0d7xOBTj3nZ0NyUZQJ6/jKwOOd5Sq4drGjNAAi2\nQkzuPQPi4DeqeUwae23BFw6QoZCBuiOWbFgfPdvxglce54d2BjuMNS8BXkoXmx6+\n3TVhkcXOLStN/7tlh868PrB1xt5jhxps0L7crJXkj+Yft/87MD4JLJhTnlFMQrM1\nXkocM55QjO7tgNUArd34OGE4Djw+3ksnIMiPZd+Alj9L+06qZ26M5+oWIrnpOk37\nET3BL03G9EmGtFSx+grSiPoqWUmh8e8fo0O1l530dQKBgQDXTgoO1NuJB4J9+8LI\n8JkUNY9gcNgdz5wguQIKdAQ45EpEB9iKTkEC+c7e7XdE/7YJ3ZWTiaZXopN0eINr\n/Ix26E8dHZcIGQd9lyRJmue4RV3Kib6wXTAvICmhNnUnXSaw5+n5TkrASLRfUGWI\nhQV4JqpeySvK0XNt+K/cUSDZMwKBgQC6xjNIkq22QyJ4YgI4th8ati2aUK1u4m9z\ntQOCqmnaDYQ6GtLq2feyd2ui8VcnS0gByaEjIFXT2EMOlvbpDknUK6SLiniJ2L0H\nwSvmjufoHXJkcxCV6dIwraTQNW4KFXRHUkUavy5gHbUhmR50m0nZIavmFEfDOSrg\nw6c0eVPJ+wKBgEAJMeyfWnwzA5i83H4vMRhVpcMlTOHw8Zq56+V4BBl3rK73NmxM\nQgHEksazEhovg9EDWo17D0JZVEn73fqsLorfJUifmGAMMbIk1eIedHTWDMNEnLaP\nwNkDU95i+A0xI2TSVUCVDJ6MATmoC8rC5ZGKznlKk//Ks+4tXQDGEGMJAoGAaLj8\nxDJJ6CnYR/tcF4Q2CHohM6cMt2GK8CuMXlVCqJpvi0zC+h9gvNYsqd376fJR2368\nNL/Kn89gOev3YVE7oBUgW4U2dMPrqU9sWAfEi+cG3r+NiyDhU53pDT0IB+tjmSHN\n0WNkk0vU5ZO++c6gJ3izs3uniRSisAfD1q44hsUCgYEAj+26b9xGQBsGxq6iIdtd\nYs4lqgFxZLP63K18sDY3fOM7L+o4PJ0Y1LZK26UbARUgw5TzOgzlukWdhAJBRUUT\n3eS6AiOMEyjnTP93hJX6d43shH+kQV17TdyvAvzTztWnblc8VJ4lJkAXpBUO7ynV\nlZEgqmHo/ojd8YxLqJKbwdM=\n-----END PRIVATE KEY-----\n",
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
        return $result;
        curl_close($ch);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function insertNotification($title, $message, $userid, $topic, $pageid, $pagename)
{
    global $con;
    $stmt = $con->prepare("INSERT INTO `notification`( `notification_title`, `notification_body`, `notification_userid`) VALUES ( ?, ?, ?)");
    $stmt->execute(
        array(
            $title,
            $message,
            $userid
        )
    );
    sendGCM($title, $message, $topic, $pageid, $pagename);
    $count = $stmt->rowCount();
    return $count;
}
