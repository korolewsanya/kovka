<?php

error_reporting(0);
ini_set('display_errors', 0);
ob_start();

define('APP_START', true);
require_once '../security.php';
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

// ЛОГИРУЕМ ВСЁ, ЧТО ПРИХОДИТ ОТ ANDROID
file_put_contents(__DIR__ . '/workers_debug.log', date('Y-m-d H:i:s') . " | POST: " . print_r($_POST, true) . "\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJson(['error' => true, 'message' => 'Method not allowed']);
}

include "../db_connection.php";

// принимаем и admin_cod, и cod (Android шлёт admin_cod)
$cod = trim($_POST['admin_cod'] ?? ($_POST['cod'] ?? ''));
if ($cod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty cod']);
}

//  проверяем только что код существует (любая роль)
// — все авторизованные пользователи могут получать список и рассылать ТЗ
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

// Отдаем список всех пользователей (БЕЗ хешей паролей)
$r = mysqli_query($conn, "SELECT `id`, `class_work`, `prof`, `name`, `id` as `cod` FROM `dostup`");
$data = array();
if ($r && mysqli_num_rows($r) > 0) {
    while ($row = mysqli_fetch_assoc($r)) {
        $data[] = $row;
    }
}
sendJson(array("dostup" => $data));
mysqli_close($conn);
