<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

define('APP_START', true);
require_once 'config.php';
require_once ROOT_DIR . 'security.php'; 
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

include ROOT_DIR . "db_connection.php";

$cod = $_POST['cod'] ?? '';
if ($cod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty cod']);
}

$isAuth = false;
$r = mysqli_query($conn, "SELECT `cod` FROM `dostup`");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($cod, $row['cod'])) {
            $isAuth = true;
            break;
        }
    }
}

if (!$isAuth) {
    sendJson(['error' => true, 'message' => 'Forbidden: invalid cod']);
}

$directory = UPLOAD_DIR;

if (!is_dir($directory)) {
    sendJson(['error' => true, 'message' => 'Папка не найдена: ' . $directory]);
}

$images = array_diff(scandir($directory), array('..', '.'));
$result = [];

foreach ($images as $image) {
    if (is_file($directory . $image)) {
        $result[] = [
            'name' => $image,
            'url' => RELATIVE_IMG_URL . rawurlencode($image)
        ];
    }
}

sendJson($result);
mysqli_close($conn);
?>