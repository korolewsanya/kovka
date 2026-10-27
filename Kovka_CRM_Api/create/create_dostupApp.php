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

// Проверка, что это админ
$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? AND class_work = 1 LIMIT 1");
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

$class_work = isset($_POST['class_work']) ? $_POST['class_work'] : '';
$prof       = isset($_POST['prof']) ? $_POST['prof'] : '';
$name       = isset($_POST['name']) ? $_POST['name'] : '';
$cod        = isset($_POST['cod']) ? $_POST['cod'] : '';

if ($class_work === '' || $prof === '' || $name === '' || $cod === '') {
    $response['error'] = true;
    $response['message'] = "Insufficient parameters";
    echo json_encode($response);
    exit;
}

$stmt = $conn->prepare("INSERT INTO `dostup`(`class_work`, `prof`, `name`, `cod`) VALUES (?,?,?,?)");
$stmt->bind_param("ssss", $class_work, $prof, $name, $cod);

if ($stmt->execute() == true) {
    $response['error'] = false;
    $response['message'] = "fin created successfully!";
} else {
    $response['error'] = true;
    $response['message'] = "failed\n " . $conn->error;
}
$stmt->close();
echo json_encode($response);
