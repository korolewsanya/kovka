<?php

define('APP_START', true);
require_once '../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("error" => true, "message" => "Method not allowed"));
    exit;
}

include "../db_connection.php";

$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';
if ($cod === '') {
    http_response_code(403);
    echo json_encode(array("error" => true, "message" => "Forbidden"));
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT id FROM dostup WHERE cod = ? AND class_work = 1 LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $cod);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$isAdmin = ($res && mysqli_num_rows($res) > 0);
mysqli_stmt_close($stmt);

if (!$isAdmin) {
    http_response_code(403);
    echo json_encode(array("error" => true, "message" => "Forbidden"));
    exit;
}

$r = mysqli_query($conn, "SELECT * FROM `fin`");
$data = array();
if ($r && mysqli_num_rows($r) > 0) {
    while ($row = mysqli_fetch_assoc($r)) {
        $data[] = $row;
    }
}
echo json_encode(array("fin" => $data), JSON_UNESCAPED_UNICODE);
mysqli_close($conn);
