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

include "../../db_connection.php";

// Принимаем и cod, и admin_cod (на случай, если Android шлет по-разному)
$adminCod = $_POST['cod'] ?? ($_POST['admin_cod'] ?? '');
if ($adminCod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty cod']);
}

// Проверяем, что код существует
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

$table = isset($_POST['table']) ? $_POST['table'] : '';

// Белый список таблиц (категорий)
$allowedTables = [
    'mangal', 'lavo4ki', 'kozirek', 'zabor',
    'vorota', 'ogradki', 'reshetki', 'mebel', 'melo4i'
];

if (!in_array($table, $allowedTables)) {
    sendJson(['error' => true, 'message' => 'Неверная таблица']);
}

$izdelie = isset($_POST['izdelie']) ? $_POST['izdelie'] : '';
$image   = isset($_POST['image']) ? $_POST['image'] : '';
$dlina   = isset($_POST['dlina']) ? $_POST['dlina'] : '';
$shirina = isset($_POST['shirina']) ? $_POST['shirina'] : '';
$visota  = isset($_POST['visota']) ? $_POST['visota'] : '';
$prise   = isset($_POST['prise']) ? (float)$_POST['prise'] : 0;

if ($izdelie === '' || $image === '') {
    sendJson(['error' => true, 'message' => 'Insufficient parameters']);
}

$stmt = $conn->prepare("INSERT INTO `$table` (`izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    sendJson(['error' => true, 'message' => 'Prepare failed: ' . $conn->error]);
}

$stmt->bind_param("sssssd", $izdelie, $image, $dlina, $shirina, $visota, $prise);

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'Успешно добавлено', 'id' => $conn->insert_id]);
} else {
    sendJson(['error' => true, 'message' => 'Execute failed: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
