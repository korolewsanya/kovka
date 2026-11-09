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

include "../../db_connection.php";

$adminCod = trim($_POST['admin_cod'] ?? ($_POST['cod'] ?? ''));
if ($adminCod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty admin_cod']);
}

$isAuth = false;
$r = mysqli_query($conn, "SELECT `cod` FROM `dostup` WHERE `class_work` IN (1,2,3,4,5,6,7)");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($adminCod, $row['cod'])) {
            $isAuth = true;
            break;
        }
    }
}

if (!$isAuth) {
    sendJson(['error' => true, 'message' => 'Forbidden: not admin/manager']);
}

$date   = isset($_POST['date'])   ? trim($_POST['date'])   : '';
$name   = isset($_POST['name'])   ? trim($_POST['name'])   : '';
$kup    = isset($_POST['kup'])    ? trim($_POST['kup'])    : '';
$izras  = isset($_POST['izras'])  ? trim($_POST['izras'])  : '';
$ost    = isset($_POST['ost'])    ? trim($_POST['ost'])    : '';
$prise  = isset($_POST['prise'])  ? trim($_POST['prise'])  : '';
$itogo  = isset($_POST['itogo'])  ? trim($_POST['itogo'])  : '';

$missing = [];
if ($date  === '') $missing[] = 'date';
if ($name  === '') $missing[] = 'name';
if ($kup   === '') $missing[] = 'kup';
if ($izras === '') $missing[] = 'izras';
if ($ost   === '') $missing[] = 'ost';
if ($prise === '') $missing[] = 'prise';
if ($itogo === '') $missing[] = 'itogo';

if (!empty($missing)) {
    sendJson(['error' => true, 'message' => 'Insufficient parameters: ' . implode(', ', $missing)]);
}

$stmt = $conn->prepare("INSERT INTO `mater`(`date`, `name`, `kup`, `izras`, `ost`, `prise`, `itogo`) VALUES (?,?,?,?,?,?,?)");
if (!$stmt) {
    sendJson(['error' => true, 'message' => 'prepare failed: ' . $conn->error]);
}

$stmt->bind_param("sssssss", $date, $name, $kup, $izras, $ost, $prise, $itogo);

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'mater created successfully!']);
} else {
    sendJson(['error' => true, 'message' => 'failed: ' . $conn->error]);
}
$stmt->close();
$conn->close();