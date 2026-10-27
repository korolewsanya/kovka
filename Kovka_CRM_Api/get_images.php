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

// Android шлет "cod" (см. ApiService.java)
$cod = $_POST['cod'] ?? '';
if ($cod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty cod']);
}

$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? LIMIT 1");
$stmt->bind_param("s", $cod);
$stmt->execute();
$res = $stmt->get_result();
$isAdmin = ($res && $res->num_rows > 0);
$stmt->close();
$conn->close();

if (!$isAdmin) {
    sendJson(['error' => true, 'message' => 'Forbidden: not admin']);
}

// Ищем файлы в папке kovka/img/
$directory = __DIR__ . '/../img/'; 

if (!is_dir($directory)) {
    sendJson(['error' => true, 'message' => 'Папка не найдена: ' . $directory]);
}

$images = array_diff(scandir($directory), array('..', '.'));
$result = [];

foreach ($images as $image) {
    if (is_file($directory . $image)) {
        $result[] = [
            'name' => $image,
            'url' => '../img/' . $image
        ];
    }
}

// Отдаем массив картинок
sendJson($result);
?>