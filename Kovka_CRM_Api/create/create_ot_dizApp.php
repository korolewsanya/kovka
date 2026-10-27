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

// Обработка файла
$targetDir = $_SERVER['DOCUMENT_ROOT'] . "/Kovka_git/kovka/img/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    $fileName = basename($_FILES['image_file']['name']);
    $targetFile = $targetDir . $fileName;
    if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)) {
        sendJson(["error" => true, "message" => "Не удалось сохранить файл"]);
    }
    $_POST['image'] = $fileName; // перезаписываем имя файла на реальное
}

$tz = $_POST['tz'] ?? '';
$ot = $_POST['ot'] ?? '';
$date = $_POST['date'] ?? '';
$prof = $_POST['prof'] ?? '';
$class_work = $_POST['class_work'] ?? '';
$name = $_POST['name'] ?? '';
$image = $_POST['image'] ?? '';

if ($tz === '' || $ot === '' || $date === '' || $prof === '' || $class_work === '' || $name === '' || $image === '') {
    sendJson(['error' => true, 'message' => 'Insufficient parameters']);
}

$stmt = $conn->prepare("INSERT INTO `otchet`(`tz`, `otchet`, `date`, `cod`, `prof`, `class_work`, `name`, `image`) VALUES (?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssss", $tz, $ot, $date, $cod, $prof, $class_work, $name, $image);

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'otchet created successfully!']);
} else {
    sendJson(['error' => true, 'message' => 'failed: ' . $conn->error]);
}
$stmt->close();
$conn->close();
?>