<?php
// Включаем вывод ошибок для отладки (потом можно убрать)
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('APP_START', true);
require_once '../security.php'; // Исправлен путь
security_headers();

// Функция должна быть объявлена ДО её первого вызова!
function sendResponse($error, $message, $fileName = '', $fileUrl = '') {
    // Очищаем буфер вывода от случайных пробелов/warnings
    while (ob_get_level() > 0) { ob_end_clean(); }
    header('Content-Type: application/json; charset=utf-8');
    $response = [
        'error' => $error,
        'message' => $message
    ];
    if ($fileName) {
        $response['fileName'] = $fileName;
    }
    if ($fileUrl) {
        $response['fileUrl'] = $fileUrl;
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// Проверка apicall
if (!isset($_GET['apicall']) || $_GET['apicall'] !== 'uploadpic') {
    sendResponse(true, 'Invalid API call');
}

require_once '../db_connection.php'; // Исправлен путь

$adminCod = isset($_POST['admin_cod']) ? trim($_POST['admin_cod']) : '';
if ($adminCod === '') {
    sendResponse(true, 'Forbidden: empty admin_cod');
}

$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? AND class_work = 1 LIMIT 1");
if (!$stmt) {
    sendResponse(true, 'DB prepare error: ' . $conn->error);
}
$stmt->bind_param("s", $adminCod);
$stmt->execute();
$res = $stmt->get_result();
$isAdmin = ($res && $res->num_rows > 0);
$stmt->close();
$conn->close();

if (!$isAdmin) {
    sendResponse(true, 'Forbidden: not admin');
}

$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Kovka_git/kovka/img/';

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        sendResponse(true, 'Cannot create upload dir: ' . $uploadDir);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pic'])) {
    $file = $_FILES['pic'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $originalName = basename($file['name']);
        $newName = time() . '_' . $originalName;
        $destination = $uploadDir . $newName;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $fileUrl = 'http://192.168.1.156/Kovka_git/kovka/img/' . $newName;
            sendResponse(false, 'File uploaded successfully', $newName, $fileUrl);
        } else {
            sendResponse(true, 'Failed to move uploaded file to: ' . $destination);
        }
    } else {
        sendResponse(true, 'Upload error code: ' . $file['error']);
    }
} else {
    sendResponse(true, 'No file or invalid request method');
}
?>