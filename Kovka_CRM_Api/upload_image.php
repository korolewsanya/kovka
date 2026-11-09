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

if (!isset($_GET['apicall']) || $_GET['apicall'] !== 'uploadpic') {
    sendJson(['error' => true, 'message' => 'Неверный вызов API']);
}

include ROOT_DIR . "db_connection.php";

$adminCod = $_POST['admin_cod'] ?? $_GET['admin_cod'] ?? $_POST['cod'] ?? '';
$adminCod = trim($adminCod);

if ($adminCod === '') {
    sendJson(['error' => true, 'message' => 'Доступ запрещён: пустой код']);
}

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
    sendJson(['error' => true, 'message' => 'Доступ запрещён: неверный код']);
}

$uploadDir = UPLOAD_DIR;
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['pic'])) {
    sendJson(['error' => true, 'message' => 'Файл не передан или неверный метод запроса']);
}

$file = $_FILES['pic'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    sendJson(['error' => true, 'message' => 'Ошибка загрузки, код: ' . $file['error']]);
}

// берём название из поля tags (то, что пользователь ввёл в EditText)
$tags = isset($_POST['tags']) ? trim($_POST['tags']) : '';

$tags = urldecode($tags);

// разрешаем кириллицу, латиницу, цифры, пробелы, - и _
// Всё остальное (/, \, :, *, ?, ", <, >, |, управляющие символы) — удаляем
$safe = preg_replace('/[^\p{Cyrillic}\p{Latin}0-9\s\-_]+/u', '', $tags);
$safe = trim(preg_replace('/\s+/u', '_', $safe));

if ($safe === '') {
    $safe = 'image';
}

// Расширение: берём из исходного имени, если неизвестное — .jpg
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, ['jpg','jpeg','png','webp','gif'], true)) {
    $ext = 'jpg';
}

// Имя = <Название>_<timestamp>.<ext> — чтобы одноимённые файлы не перезаписывались
$newName = $safe . '_' . time() . '.' . $ext;
$destination = $uploadDir . $newName;

if (move_uploaded_file($file['tmp_name'], $destination)) {
    // ВАЖНО: для кириллицы rawurlencode обязателен — иначе Android/браузер
    // не найдёт файл по URL (получит %D0%9A... вместо К)
    $fileUrl = UPLOAD_URL . rawurlencode($newName);
    sendJson([
        'error'    => false,
        'message'  => 'Файл успешно загружен',
        'fileName' => $newName,
        'fileUrl'  => $fileUrl
    ]);
} else {
    sendJson(['error' => true, 'message' => 'Не удалось сохранить файл']);
}