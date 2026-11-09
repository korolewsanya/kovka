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

include "../../db_connection.php";

$adminCod = $_POST['admin_cod'] ?? '';
if ($adminCod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty admin_cod']);
}

$r = mysqli_query($conn, "SELECT `id`, `cod` FROM `dostup` WHERE `class_work` = 1");
$isAdmin = false;
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($adminCod, $row['cod'])) {
            $isAdmin = true;
            break;
        }
    }
}

if (!$isAdmin) {
    sendJson(['error' => true, 'message' => 'Forbidden: not admin']);
}

$id         = isset($_POST['id'])         ? $_POST['id']         : '';
$class_work = isset($_POST['class_work']) ? $_POST['class_work'] : '';
$prof       = isset($_POST['prof'])       ? $_POST['prof']       : '';
$name       = isset($_POST['name'])       ? $_POST['name']       : '';
$cod        = isset($_POST['cod'])        ? trim($_POST['cod'])  : '';

if ($id === '' || $class_work === '' || $prof === '' || $name === '') {
    sendJson(['error' => true, 'message' => 'Insufficient parameters']);
}

if ($cod !== '') {
    // Если админ ввел новый код — хешируем его
    $codHash = password_hash($cod, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE dostup SET class_work = ?, prof = ?, name = ?, cod = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $class_work, $prof, $name, $codHash, $id);
} else {
    // Если код не менялся — обновляем только остальные поля
    $stmt = $conn->prepare("UPDATE dostup SET class_work = ?, prof = ?, name = ? WHERE id = ?");
    $stmt->bind_param("sssi", $class_work, $prof, $name, $id);
}

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'Данные пользователя обновлены']);
} else {
    sendJson(['error' => true, 'message' => 'failed: ' . $conn->error]);
}
$stmt->close();
$conn->close();
?>