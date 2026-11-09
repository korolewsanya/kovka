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

$tz = isset($_POST['tz']) ? $_POST['tz'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';
$cod = isset($_POST['cod']) ? $_POST['cod'] : ''; // ID выбранного сотрудника
$prof = isset($_POST['prof']) ? $_POST['prof'] : '';
$class_work = isset($_POST['class_work']) ? $_POST['class_work'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';

if ($tz === '' || $date === '' || $cod === '' || $prof === '' || $class_work === '' || $name === '') {
    sendJson(['error' => true, 'message' => 'Insufficient parameters']);
}

$stmt = $conn->prepare("INSERT INTO `otchet`(`tz`, `date`, `cod`, `prof`, `class_work`, `name`) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("ssssss", $tz, $date, $cod, $prof, $class_work, $name);

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'Задание создано']);
} else {
    sendJson(['error' => true, 'message' => 'failed: ' . $conn->error]);
}
$stmt->close();
$conn->close();
