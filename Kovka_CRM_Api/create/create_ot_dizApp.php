<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

// 1. Ищем пользователя по коду
$foundClassWork = null;
$foundProf = null;
$foundName = null;
$r = mysqli_query($conn, "SELECT `cod`, `prof`, `name`, `class_work` FROM `dostup`");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($cod, $row['cod'])) {
            $foundClassWork = $row['class_work']; // это user_id
            $foundProf = $row['prof'];
            $foundName = $row['name'];
            break;
        }
    }
}

if ($foundClassWork === null) {
    sendJson(['error' => true, 'message' => 'Forbidden: invalid cod']);
}

require_once '../config.php';

$targetDir = UPLOAD_DIR;
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    $fileName = basename($_FILES['image_file']['name']);
    $targetFile = $targetDir . $fileName;
    if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)) {
        sendJson(["error" => true, "message" => "Не удалось сохранить файл"]);
    }
    $_POST['image'] = $fileName;
}

$tz = $_POST['tz'] ?? '';
$ot = $_POST['ot'] ?? '';
$date = $_POST['date'] ?? '';
$image = $_POST['image'] ?? '';

if ($tz === '' || $ot === '' || $date === '' || $image === '') {
    sendJson(['error' => true, 'message' => 'Insufficient parameters']);
}

// 2. Записываем в otchet class_work (user_id), prof, name из dostup
$stmt = $conn->prepare("INSERT INTO `otchet`(`tz`, `otchet`, `date`, `cod`, `prof`, `class_work`, `name`, `image`) VALUES (?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssss", $tz, $ot, $date, $cod, $foundProf, $foundClassWork, $foundName, $image);

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'otchet created successfully!']);
} else {
    sendJson(['error' => true, 'message' => 'failed: ' . $conn->error]);
}
$stmt->close();
$conn->close();
?>