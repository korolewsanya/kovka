<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

include "../db_connection.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array("error" => "Method Not Allowed"), JSON_UNESCAPED_UNICODE);
    exit;
}

$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';

if ($cod === '') {
    echo json_encode(array("success" => false, "error" => "empty_code"), JSON_UNESCAPED_UNICODE);
    exit;
}

$r = mysqli_query($conn, "SELECT `id`, `class_work`, `prof`, `name`, `cod` FROM `dostup`");
$found = null;

if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($cod, $row['cod'])) {
            $found = $row;
            break;
        }
    }
}

if ($found) {
    echo json_encode(array(
        "success" => true,
        "user" => array(
            "id"         => (int)$found['id'],
            "class_work" => (int)$found['class_work'],
            "prof"       => $found['prof'],
            "name"       => $found['name']
        )
    ), JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(array("success" => false, "error" => "invalid_code"), JSON_UNESCAPED_UNICODE);
}
mysqli_close($conn);
?>