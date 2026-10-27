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

// ИЗМЕНЕНО: Добавлена проверка admin_cod (только админ может менять ТЗ)
$adminCod = isset($_POST['admin_cod']) ? trim($_POST['admin_cod']) : '';
if ($adminCod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty admin_cod']);
}

$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? AND class_work = 1 LIMIT 1");
$stmt->bind_param("s", $adminCod);
$stmt->execute();
$res = $stmt->get_result();
$isAdmin = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAdmin) {
    sendJson(['error' => true, 'message' => 'Forbidden: not admin']);
}

if (isset($_POST['id'], $_POST['tz'])) {
    $id = (int)$_POST['id'];
    $tz = $_POST['tz'];

    $stmt = $conn->prepare("UPDATE otchet SET tz = ? WHERE id = ?");
    $stmt->bind_param("si", $tz, $id);
    if ($stmt->execute()) {
        sendJson(['error' => false, 'message' => 'Обновлено']);
    } else {
        sendJson(['error' => true, 'message' => 'Ошибка БД: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    sendJson(['error' => true, 'message' => 'Недостаточно параметров']);
}
?>