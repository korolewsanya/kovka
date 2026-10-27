<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

define('APP_START', true);
require_once '../../security.php';
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

require_once '../../db_connection.php';

$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';
if ($cod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty cod']);
}

$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? LIMIT 1");
$stmt->bind_param("s", $cod);
$stmt->execute();
$res = $stmt->get_result();
$isAuth = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAuth) {
    sendJson(['error' => true, 'message' => 'Forbidden: invalid cod']);
}

if (isset($_POST['id']) && isset($_POST['otchet']) && $_POST['id'] !== '' && $_POST['otchet'] !== '') {
    $id = intval($_POST['id']);
    $otchet = $_POST['otchet'];

    $stmt = $conn->prepare("UPDATE otchet SET otchet = ? WHERE id = ?");
    $stmt->bind_param("si", $otchet, $id);

    if ($stmt->execute()) {
        sendJson(['error' => false, 'message' => 'otchet change successfully!']);
    } else {
        sendJson(['error' => true, 'message' => 'failed: ' . $conn->error]);
    }
    $stmt->close();
} else {
    sendJson(['error' => true, 'message' => 'Insufficient parameters']);
}
$conn->close();
?>