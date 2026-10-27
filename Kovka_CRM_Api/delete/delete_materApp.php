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

$adminCod = isset($_POST['admin_cod']) ? trim($_POST['admin_cod']) : '';
if ($adminCod === '') {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("error" => true, "message" => "Forbidden"));
    exit;
}

$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? LIMIT 1");
$stmt->bind_param("s", $adminCod);
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

$id = isset($_POST['id']) ? $_POST['id'] : '';
if ($id === '') {
    echo json_encode(array("error" => true, "message" => "Insufficient Parameters"));
    exit;
}

$stmt = $conn->prepare("DELETE FROM mater WHERE id = ?");
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    $response['error'] = false;
    $response['message'] = "deleted successfully!";
} else {
    $response['error'] = true;
    $response['message'] = "failed\n " . $conn->error;
}
$stmt->close();
echo json_encode($response);
