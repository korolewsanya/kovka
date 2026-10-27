<?php

define('APP_START', true);
require_once '../security.php';
security_headers();

include "../db_connection.php";

// Разрешаем только POST
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

$stmt = mysqli_prepare($conn, "SELECT `id`, `class_work`, `prof`, `name` FROM `dostup` WHERE `cod` = ? LIMIT 1");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(array("success" => false, "error" => "db_error"), JSON_UNESCAPED_UNICODE);
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $cod);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode(array(
        "success" => true,
        "user" => array(
            "id"         => (int)$row['id'],
            "class_work" => (int)$row['class_work'],
            "prof"       => $row['prof'],
            "name"       => $row['name']
        )
    ), JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(array("success" => false, "error" => "invalid_code"), JSON_UNESCAPED_UNICODE);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
