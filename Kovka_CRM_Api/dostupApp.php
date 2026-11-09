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

// Android передает код под именем "admin_cod" или "cod"
$adminCod = $_POST['admin_cod'] ?? ($_POST['cod'] ?? '');
if ($adminCod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty code']);
}

// Ищем админа (class_work = 1) и сверяем хеш
$r = mysqli_query($conn, "SELECT `id`, `cod` FROM `dostup` WHERE `class_work` = 1");
$isAdmin = false;
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($adminCod, $row['cod'])) {
            $isAdmin = true;
            break;
        }
    }
}

if (!$isAdmin) {
    sendJson(['error' => true, 'message' => 'Forbidden: not admin']);
}

// Отдаем список всех пользователей (БЕЗ хешей паролей!)
$r = mysqli_query($conn, "SELECT `id`, `class_work`, `prof`, `name` FROM `dostup`");
$data = array();
if ($r && mysqli_num_rows($r) > 0) {
    while ($row = mysqli_fetch_assoc($r)) {
        $data[] = $row;
    }
}
sendJson(array("dostup" => $data));
mysqli_close($conn);
?>