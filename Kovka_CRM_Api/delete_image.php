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

$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? AND class_work IN (1, 4) LIMIT 1");
$stmt->bind_param("s", $adminCod);
$stmt->execute();
$res = $stmt->get_result();
$isAdmin = ($res && $res->num_rows > 0);
$stmt->close();
$conn->close();

if (!$isAdmin) {
    sendJson(['success' => false, 'error' => 'Forbidden: not admin']);
}

$filename = basename($_POST['filename'] ?? '');
$filepath = __DIR__ . '/../img/' . $filename;

if (file_exists($filepath)) {
    if (unlink($filepath)) {
        sendJson(['success' => true]); // <-- Формат под ApiResponse
    } else {
        sendJson(['success' => false, 'error' => 'Cannot delete file']);
    }
} else {
    sendJson(['success' => false, 'error' => 'File not found']);
}
?>