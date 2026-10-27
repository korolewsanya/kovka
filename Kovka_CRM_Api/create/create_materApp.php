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

$date   = isset($_POST['date']) ? $_POST['date'] : '';
$name   = isset($_POST['name']) ? $_POST['name'] : '';
$kup    = isset($_POST['kup']) ? $_POST['kup'] : '';
$izras  = isset($_POST['izras']) ? $_POST['izras'] : '';
$ost    = isset($_POST['ost']) ? $_POST['ost'] : '';
$prise  = isset($_POST['prise']) ? $_POST['prise'] : '';
$itogo  = isset($_POST['itogo']) ? $_POST['itogo'] : '';

if ($date === '' || $name === '' || $kup === '' || $izras === '' || $ost === '' || $prise === '' || $itogo === '') {
    echo json_encode(array("error" => true, "message" => "Insufficient parameters"));
    exit;
}

$stmt = $conn->prepare("INSERT INTO `mater`(`date`, `name`, `kup`, `izras`, `ost`, `prise`, `itogo`) VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("sssssss", $date, $name, $kup, $izras, $ost, $prise, $itogo);

if ($stmt->execute()) {
    $response['error'] = false;
    $response['message'] = "mater created successfully!";
} else {
    $response['error'] = true;
    $response['message'] = "failed\n " . $conn->error;
}
$stmt->close();
echo json_encode($response);
