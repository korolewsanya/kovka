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

// ИЗМЕНЕНО: Добавлена проверка admin_cod (создавать задания может только админ)
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

// ИЗМЕНЕНО: безопасное получение параметров
$tz = isset($_POST['tz']) ? $_POST['tz'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';
$cod = isset($_POST['cod']) ? $_POST['cod'] : '';
$prof = isset($_POST['prof']) ? $_POST['prof'] : '';
$class_work = isset($_POST['class_work']) ? $_POST['class_work'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';

if ($tz === '' || $date === '' || $cod === '' || $prof === '' || $class_work === '' || $name === '') {
    sendJson(['error' => true, 'message' => 'Insufficient parameters']);
}

$stmt = $conn->prepare("INSERT INTO `otchet`(`tz`, `date`, `cod`, `prof`, `class_work`, `name`) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("ssssss", $tz, $date, $cod, $prof, $class_work, $name);

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'zadachi created successfully!']);
} else {
    sendJson(['error' => true, 'message' => 'failed: ' . $conn->error]);
}
$stmt->close();
$conn->close();
?>