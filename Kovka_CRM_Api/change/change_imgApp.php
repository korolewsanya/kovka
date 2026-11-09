<?php

error_reporting(0);
ini_set('display_errors', 0);
ob_start();

define('APP_START', true);
require_once '../../security.php';
security_headers();

function sendJson($data)
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJson(['error' => true, 'message' => 'Method not allowed']);
}

require_once '../../db_connection.php';

$adminCod = $_POST['admin_cod'] ?? ($_POST['cod'] ?? '');
if ($adminCod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty admin_cod']);
}

// Проверка админа через password_verify
$isAdmin = false;
$r = mysqli_query($conn, "SELECT `cod` FROM `dostup` WHERE `class_work` IN (1, 2)");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($adminCod, $row['cod'])) {
            $isAdmin = true;
            break;
        }
    }
}

if (!$isAdmin) {
    sendJson(['error' => true, 'message' => 'Forbidden: not admin/designer']);
}

$allowedTables = [
    'mangal', 'lavo4ki', 'kozirek', 'zabor',
    'vorota', 'ogradki', 'reshetki', 'mebel', 'melo4i'
];

if (empty($_POST['id']) || empty($_POST['category']) || !isset($_POST['image']) || !isset($_POST['izdelie'])) {
    sendJson(['error' => true, 'message' => 'Недостаточно параметров']);
}

$id       = (int)$_POST['id'];
$category = $_POST['category'];
$image    = $_POST['image'];
$izdelie  = $_POST['izdelie'];

if (!in_array($category, $allowedTables)) {
    sendJson(['error' => true, 'message' => "Недопустимая категория: $category"]);
}

$sql = "UPDATE `$category` SET image = ?, izdelie = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    sendJson(['error' => true, 'message' => 'Ошибка подготовки запроса: ' . $conn->error]);
}

$stmt->bind_param('ssi', $image, $izdelie, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        sendJson(['error' => false, 'message' => 'Товар обновлён']);
    } else {
        sendJson(['error' => false, 'message' => 'Запрос выполнен, но запись не изменена']);
    }
} else {
    sendJson(['error' => true, 'message' => 'Ошибка выполнения: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
