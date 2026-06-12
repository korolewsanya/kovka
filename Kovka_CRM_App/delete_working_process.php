<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => true, 'message' => 'Method not allowed']);
    exit;
}
// //csrf_check(); // при необходимости

include "../db_connection.php";

$response = [];
if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM otchet WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $response = ['error' => false, 'message' => 'Удалено'];
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