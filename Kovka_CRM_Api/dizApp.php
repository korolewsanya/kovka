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

$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';
if ($cod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty cod']);
}

$stmt = $conn->prepare("SELECT * FROM otchet WHERE cod = ?");
$stmt->bind_param("s", $cod);
$stmt->execute();
$result = $stmt->get_result();

$data = array();
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
$stmt->close();
$conn->close();

sendJson(array("tz" => $data));
?>