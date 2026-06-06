<?php
define('APP_START', true);
require_once '../security.php';
security_headers();
require_post();          // разрешаем только POST
csrf_check();           // проверяем CSRF-токен

$uploadDir = '../img/';
$file = basename($_POST['file'] ?? '');
if ($file && file_exists($uploadDir . $file)) {
    unlink($uploadDir . $file);
}
header('Location: img_list.php');
exit;
?>