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
    sendJson(['success' => false, 'error' => 'Метод не разрешён']);
}

include ROOT_DIR . "db_connection.php";

// принимаем admin_cod и из POST, и из GET; trim
$adminCod = trim($_POST['admin_cod'] ?? ($_GET['admin_cod'] ?? ($_POST['cod'] ?? '')));

if ($adminCod === '') {
    sendJson(['success' => false, 'error' => 'Доступ запрещён: пустой код']);
}

// проверка роли — только админ (1) и дизайнер (2)
$isAllowed = false;
$r = mysqli_query($conn, "SELECT `cod`, `class_work` FROM `dostup`");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($adminCod, $row['cod'])) {
            $cw = (int)$row['class_work'];
            if (in_array($cw, [1, 2], true)) {
                $isAllowed = true;
            } else {
                sendJson(['success' => false, 'error' => 'Недостаточно прав: удалять изображения могут только администратор и дизайнер']);
            }
            break;
        }
    }
}

if (!$isAllowed) {
    sendJson(['success' => false, 'error' => 'Доступ запрещён: неверный код']);
}

// basename защищает от ../ и слэшей
$filename = basename($_POST['filename'] ?? '');
if ($filename === '' || $filename === '.' || $filename === '..') {
    sendJson(['success' => false, 'error' => 'Некорректное имя файла']);
}

// дополнительно проверяем, что файл — картинка
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExtensions, true)) {
    sendJson(['success' => false, 'error' => 'Можно удалять только файлы изображений']);
}

$filepath = UPLOAD_DIR . $filename;

// проверяем, что путь реально внутри UPLOAD_DIR (защита от symlink/обхода)
$realUpload = realpath(UPLOAD_DIR);
$realFile   = realpath($filepath);
if ($realUpload === false || $realFile === false || strpos($realFile, $realUpload) !== 0) {
    sendJson(['success' => false, 'error' => 'Файл не найден']);
}

if (is_file($realFile)) {
    if (unlink($realFile)) {
        sendJson(['success' => true, 'message' => 'Файл удалён']);
    } else {
        sendJson(['success' => false, 'error' => 'Не удалось удалить файл']);
    }
} else {
    sendJson(['success' => false, 'error' => 'Файл не найден']);
}