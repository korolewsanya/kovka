<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

define('APP_START', true);
require_once '../security.php';
security_headers();

function sendJson($data) {
    while (ob_get_level() > 0) { ob_end_clean(); }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJson(['error' => true, 'message' => 'Method not allowed']);
}

include "../db_connection.php";

$adminCod = isset($_POST['admin_cod']) ? trim($_POST['admin_cod']) : '';
if ($adminCod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty admin_cod']);
}

// Разрешаем менять картинку всем авторизованным (админам и дизайнерам)
$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? LIMIT 1");
$stmt->bind_param("s", $adminCod);
$stmt->execute();
$res = $stmt->get_result();
$isAuth = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAuth) {
    sendJson(['error' => true, 'message' => 'Forbidden: invalid cod']);
}

$img = isset($_POST['img']) ? trim($_POST['img']) : '';

$stmt = $conn->prepare("UPDATE img SET img = ? WHERE id = '1'");
$stmt->bind_param("s", $img);

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'Изображение обновлено']);
} else {
    sendJson(['error' => true, 'message' => 'Ошибка БД: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>