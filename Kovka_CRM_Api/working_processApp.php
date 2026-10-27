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

// ИЗМЕНЕНО: добавлена проверка кода доступа (cod)
$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';
if ($cod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty cod']);
}

$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? LIMIT 1");
$stmt->bind_param("s", $cod);
$stmt->execute();
$res = $stmt->get_result();
$isAuth = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAuth) {
    sendJson(['error' => true, 'message' => 'Forbidden: invalid cod']);
}

$sql_query = "SELECT * FROM `otchet`";
$r = mysqli_query($conn, $sql_query);
$data = array();

if ($r && mysqli_num_rows($r) > 0) {
    while($row = mysqli_fetch_assoc($r)) {
        $data[] = $row;
    }
}
sendJson(array("working_process" => $data));
mysqli_close($conn);
?>