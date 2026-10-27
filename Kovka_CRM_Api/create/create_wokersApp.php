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

$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? AND class_work = 1 LIMIT 1");
$stmt->bind_param("s", $adminCod);
$stmt->execute();
$res = $stmt->get_result();
$isAdmin = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAdmin) {
    sendJson(['error' => true, 'message' => 'Forbidden: not admin']);
}

$spec  = isset($_POST['spec'])  ? $_POST['spec']  : '';
$name  = isset($_POST['name'])  ? $_POST['name']  : '';
$tel   = isset($_POST['tel'])   ? $_POST['tel']   : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$adres = isset($_POST['adres']) ? $_POST['adres'] : '';
$data  = isset($_POST['data'])  ? $_POST['data']  : '';
$proch = isset($_POST['proch']) ? $_POST['proch'] : '';

if ($spec === '' || $name === '' || $tel === '' || $email === '' || $adres === '' || $data === '') {
    sendJson(['error' => true, 'message' => 'Insufficient parameters']);
}

$stmt = $conn->prepare("INSERT INTO `workes`(`spec`, `name`, `tel`, `email`, `adres`, `data`, `proch`) VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("sssssss", $spec, $name, $tel, $email, $adres, $data, $proch);

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'wokers created successfully!']);
} else {
    sendJson(['error' => true, 'message' => 'failed: ' . $conn->error]);
}
$stmt->close();
$conn->close();
?>