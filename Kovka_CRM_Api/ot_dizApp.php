<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

$debugInfo = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJson(['error' => true, 'message' => 'Method not allowed', 'debug' => ['Wrong method']]);
}

require_once '../db_connection.php';

$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';
$debugInfo[] = "Получен cod: [" . $cod . "]";

if ($cod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty cod', 'debug' => $debugInfo]);
}

// 1. Ищем пользователя по коду и получаем его class_work (user_id)
$foundClassWork = null;
$r = mysqli_query($conn, "SELECT `cod`, `class_work` FROM `dostup`");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($cod, $row['cod'])) {
            $foundClassWork = $row['class_work'];
            $debugInfo[] = "Найден пользователь: class_work (user_id) = " . $foundClassWork;
            break;
        }
    }
}

if ($foundClassWork === null) {
    $debugInfo[] = "ОШИБКА: пользователь не найден";
    sendJson(['error' => true, 'message' => 'Forbidden: invalid cod', 'debug' => $debugInfo]);
}

// 2. Ищем отчеты по class_work (user_id)
$stmt = $conn->prepare("SELECT * FROM `otchet` WHERE `class_work` = ?");
$stmt->bind_param("s", $foundClassWork);
$stmt->execute();
$result = $stmt->get_result();

$data = array();
if ($result && $result->num_rows > 0) {
    $debugInfo[] = "Найдено отчетов: " . $result->num_rows;
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $debugInfo[] = "Отчетов не найдено для class_work = " . $foundClassWork;
}

$stmt->close();
$conn->close();

sendJson(array("ot_diz" => $data, "debug" => $debugInfo));
?>