<?php

define('APP_START', true);
require_once '../security.php';   // поправьте путь, если cod.php лежит в другой папке
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

include "../db_connection.php";

$response = array();

$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';

if ($cod === '') {
    $response['error'] = true;
    $response['message'] = "Insufficient parameters";
    echo json_encode($response);
    exit;
}

// Проверяем, что такой код существует
$stmt = mysqli_prepare($conn, "SELECT id FROM dostup WHERE cod = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $cod);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$exists = ($res && mysqli_num_rows($res) > 0);
mysqli_stmt_close($stmt);

if (!$exists) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("error" => true, "message" => "Forbidden"));
    exit;
}

// Пишем код в БД (как было у вас)
$stmt = mysqli_prepare($conn, "INSERT INTO `cod`(`cod`) VALUES (?)");
mysqli_stmt_bind_param($stmt, "s", $cod);

if (mysqli_stmt_execute($stmt)) {
    $response['error'] = false;
    $response['message'] = "cod saved successfully!";
} else {
    $response['error'] = true;
    $response['message'] = "failed\n " . $conn->error;
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
echo json_encode($response);
