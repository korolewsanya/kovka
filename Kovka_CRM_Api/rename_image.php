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
    sendJson(['success' => false, 'error' => 'Method not allowed']);
}

include "../db_connection.php";

$adminCod = $_POST['admin_cod'] ?? '';
if ($adminCod === '') {
    sendJson(['success' => false, 'error' => 'Forbidden: empty admin_cod']);
}

// ИСПРАВЛЕНО: Добавлено слово IN перед списком ролей
$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? AND class_work IN (1, 2, 4) LIMIT 1");
$stmt->bind_param("s", $adminCod);
$stmt->execute();
$res = $stmt->get_result();
$isAdmin = ($res && $res->num_rows > 0);
$stmt->close();
$conn->close();

if (!$isAdmin) {
    sendJson(['success' => false, 'error' => 'Forbidden: not admin']);
}

$oldName = basename($_POST['old_name'] ?? '');
$newName = basename($_POST['new_name'] ?? '');

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$ext = pathinfo($newName, PATHINFO_EXTENSION);

if (!in_array(strtolower($ext), $allowedExtensions)) {
    sendJson(['success' => false, 'error' => 'Invalid file extension']);
}

$oldPath = __DIR__ . '/../img/' . $oldName;
$newPath = __DIR__ . '/../img/' . $newName;

if (file_exists($oldPath)) {
    if (rename($oldPath, $newPath)) {
        sendJson(['success' => true, 'new_name' => $newName]);
    } else {
        sendJson(['success' => false, 'error' => 'Cannot rename file']);
    }
} else {
    sendJson(['success' => false, 'error' => 'File not found']);
}
?>