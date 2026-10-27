<?php

define('APP_START', true);
require_once '../../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

require_once '../../db_connection.php';

$response = array();

$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';
if ($cod === '') {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("error" => true, "message" => "Forbidden"));
    exit;
}

$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? AND class_work = 1 LIMIT 1");
$stmt->bind_param("s", $cod);
$stmt->execute();
$res = $stmt->get_result();
$isAdmin = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAdmin) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("error" => true, "message" => "Forbidden"));
    exit;
}

$date   = isset($_POST['date']) ? $_POST['date'] : '';
$dohod  = isset($_POST['dohod']) ? $_POST['dohod'] : '';
$rashod = isset($_POST['rashod']) ? $_POST['rashod'] : '';
$prib   = isset($_POST['prib']) ? $_POST['prib'] : '';

if ($date === '' || $dohod === '' || $rashod === '' || $prib === '') {
    echo json_encode(array("error" => true, "message" => "Insufficient parameters"));
    exit;
}

$stmt = $conn->prepare("INSERT INTO `fin`(`date`, `dohod`, `rashod`, `prib`) VALUES (?,?,?,?)");
$stmt->bind_param("ssss", $date, $dohod, $rashod, $prib);

if ($stmt->execute()) {
    $response['error'] = false;
    $response['message'] = "fin created successfully!";
} else {
    $response['error'] = true;
    $response['message'] = "failed\n " . $conn->error;
}
$stmt->close();
echo json_encode($response);
