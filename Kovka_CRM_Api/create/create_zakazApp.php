<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

define('APP_START', true);
require_once '../../security.php';
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

require_once '../../db_connection.php';

$adminCod = isset($_POST['admin_cod']) ? trim($_POST['admin_cod']) : '';
if ($adminCod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty admin_cod']);
}

$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? LIMIT 1");
$stmt->bind_param("s", $adminCod);
$stmt->execute();
$res = $stmt->get_result();
$isAuth = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAuth) {
    sendJson(['error' => true, 'message' => 'Forbidden: invalid admin_cod']);
}

$data = isset($_POST['date']) ? $_POST['date'] : '';
$izdelie = isset($_POST['izdelie']) ? $_POST['izdelie'] : '';
$image = isset($_POST['image']) ? $_POST['image'] : '';
$dlina = isset($_POST['dlina']) ? $_POST['dlina'] : '';
$shirina = isset($_POST['shirina']) ? $_POST['shirina'] : '';
$visota = isset($_POST['visota']) ? $_POST['visota'] : '';
$prise = isset($_POST['prise']) ? $_POST['prise'] : '';
$pay = isset($_POST['pay']) ? $_POST['pay'] : '';
$proces = isset($_POST['proces']) ? $_POST['proces'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$tel = isset($_POST['tel']) ? $_POST['tel'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$coment = isset($_POST['coment']) ? $_POST['coment'] : '';

if ($data === '' || $izdelie === '' || $prise === '' || $pay === '' || $tel === '' || $email === '') {
    sendJson(['error' => true, 'message' => 'Insufficient parameters']);
}

$stmt = $conn->prepare("INSERT INTO `zakaz`(`date`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`, `Pay`, `Proces`, `Name`, `Tel`, `Email`, `Coment`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
$stmt->bind_param("sssssssssssss", $data, $izdelie, $image, $dlina, $shirina, $visota, $prise, $pay, $proces, $name, $tel, $email, $coment);

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'zakaz created successfully!']);
} else {
    sendJson(['error' => true, 'message' => 'failed: ' . $conn->error]);
}
$stmt->close();
$conn->close();
?>