<?php

define('APP_START', true);
require_once '../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

include "../db_connection.php";

// Принимаем и admin_cod, и cod (на случай путаницы в Android)
$adminCod = '';
if (isset($_POST['admin_cod']) && $_POST['admin_cod'] !== '') {
    $adminCod = trim($_POST['admin_cod']);
} elseif (isset($_POST['cod']) && $_POST['cod'] !== '') {
    $adminCod = trim($_POST['cod']);
}

if ($adminCod === '') {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("error" => true, "message" => "Forbidden: empty admin_cod"), JSON_UNESCAPED_UNICODE);
    exit;
}

// Проверяем, что это админ (class_work = 1)
$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? AND class_work = 1 LIMIT 1");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(array("error" => true, "message" => "DB error"));
    exit;
}
$stmt->bind_param("s", $adminCod);
$stmt->execute();
$res = $stmt->get_result();
$isAdmin = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAdmin) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("error" => true, "message" => "Forbidden: not admin"), JSON_UNESCAPED_UNICODE);
    exit;
}

$r = mysqli_query($conn, "SELECT * FROM `dostup`");
$data = array();
if ($r && mysqli_num_rows($r) > 0) {
    while ($row = mysqli_fetch_assoc($r)) {
        $data[] = $row;
    }
}
echo json_encode(array("dostup" => $data), JSON_UNESCAPED_UNICODE);
mysqli_close($conn);
