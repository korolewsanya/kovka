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

$adminCod = $_POST['admin_cod'] ?? ($_POST['cod'] ?? '');
if ($adminCod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty code']);
}

// Проверяем, что код существует (любая роль)
$isAuth = false;
$r = mysqli_query($conn, "SELECT `cod` FROM `dostup`");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($adminCod, $row['cod'])) {
            $isAuth = true;
            break;
        }
    }
}

if (!$isAuth) {
    sendJson(['error' => true, 'message' => 'Forbidden: invalid cod']);
}

if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM otchet WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        sendJson(['error' => false, 'message' => 'Удалено']);
    } else {
        sendJson(['error' => true, 'message' => 'Ошибка БД: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    sendJson(['error' => true, 'message' => 'Недостаточно параметров']);
}
$conn->close();
?>