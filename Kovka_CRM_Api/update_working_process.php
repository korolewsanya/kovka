<?php

define('APP_START', true);
require_once '../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => true, 'message' => 'Method not allowed']);
    exit;
}
// Если используете CSRF-токен, раскомментируйте //csrf_check(); и передавайте token из Android
// //csrf_check();

include "../db_connection.php";

$response = [];
if (isset($_POST['id'], $_POST['tz'])) {
    $id = (int)$_POST['id'];
    $tz = $_POST['tz'];

    $stmt = $conn->prepare("UPDATE otchet SET tz = ? WHERE id = ?");
    $stmt->bind_param("si", $tz, $id);
    if ($stmt->execute()) {
        $response = ['error' => false, 'message' => 'Обновлено'];
    } else {
        $response = ['error' => true, 'message' => 'Ошибка БД: ' . $stmt->error];
    }
    $stmt->close();
} else {
    $response = ['error' => true, 'message' => 'Недостаточно параметров'];
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
?>