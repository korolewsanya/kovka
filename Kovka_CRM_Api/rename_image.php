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

//принимаем admin_cod и из POST, и из GET; trim
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
                sendJson(['success' => false, 'error' => 'Недостаточно прав: переименовывать изображения могут только администратор и дизайнер']);
            }
            break;
        }
    }
}

if (!$isAllowed) {
    sendJson(['success' => false, 'error' => 'Доступ запрещён: неверный код']);
}

// basename защищает от ../ и слэшей
$oldName = basename($_POST['old_name'] ?? '');
$newName = basename($_POST['new_name'] ?? '');

if ($oldName === '' || $oldName === '.' || $oldName === '..') {
    sendJson(['success' => false, 'error' => 'Некорректное исходное имя файла']);
}
if ($newName === '' || $newName === '.' || $newName === '..') {
    sendJson(['success' => false, 'error' => 'Некорректное новое имя файла']);
}

// разрешаем только изображения — и по старому, и по новому имени
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

$oldExt = strtolower(pathinfo($oldName, PATHINFO_EXTENSION));
if (!in_array($oldExt, $allowedExtensions, true)) {
    sendJson(['success' => false, 'error' => 'Исходный файл — не изображение']);
}

$newExt = strtolower(pathinfo($newName, PATHINFO_EXTENSION));
if (!in_array($newExt, $allowedExtensions, true)) {
    sendJson(['success' => false, 'error' => 'Недопустимое расширение нового файла']);
}

$oldPath = UPLOAD_DIR . $oldName;
$newPath = UPLOAD_DIR . $newName;

// проверяем, что оба пути реально внутри UPLOAD_DIR
$realUpload = realpath(UPLOAD_DIR);
if ($realUpload === false) {
    sendJson(['success' => false, 'error' => 'Каталог загрузок недоступен']);
}

$realOld = realpath($oldPath);
if ($realOld === false || strpos($realOld, $realUpload) !== 0) {
    sendJson(['success' => false, 'error' => 'Исходный файл не найден']);
}

// не даём перезаписать существующий файл
if (file_exists($newPath)) {
    sendJson(['success' => false, 'error' => 'Файл с таким именем уже существует']);
}

if (rename($realOld, $newPath)) {
    sendJson(['success' => true, 'new_name' => $newName, 'message' => 'Файл переименован']);
} else {
    sendJson(['success' => false, 'error' => 'Не удалось переименовать файл']);
}