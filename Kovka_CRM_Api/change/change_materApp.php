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

$adminCod = isset($_POST['admin_cod']) ? trim($_POST['admin_cod']) : '';
if ($adminCod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty admin_cod']);
}

$isAdmin = false;
$r = mysqli_query($conn, "SELECT `cod` FROM `dostup` WHERE `class_work` IN (1,2,3,4,5,6,7)");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($adminCod, $row['cod'])) {
            $isAdmin = true;
            break;
        }
    }
}

if (!$isAdmin) {
    sendJson(['error' => true, 'message' => 'Forbidden: not admin/manager']);
}

$id     = isset($_POST['id'])     ? $_POST['id']     : '';
$date   = isset($_POST['date'])   ? $_POST['date']   : '';
$name   = isset($_POST['name'])   ? $_POST['name']   : '';
$kup    = isset($_POST['kup'])    ? $_POST['kup']    : '';
$izras  = isset($_POST['izras'])  ? $_POST['izras']  : '';
$ost    = isset($_POST['ost'])    ? $_POST['ost']    : '';
$prise  = isset($_POST['prise'])  ? $_POST['prise']  : '';
$itogo  = isset($_POST['itogo'])  ? $_POST['itogo']  : '';

if ($id === '' || $date === '' || $name === '' || $kup === '' || $izras === '' || $ost === '' || $prise === '' || $itogo === '') {
    sendJson(['error' => true, 'message' => 'Insufficient parameters']);
}

$stmt = $conn->prepare("UPDATE mater SET date = ?, name = ?, kup = ?, izras = ?, ost = ?, prise = ?, itogo = ? WHERE id = ?");
$stmt->bind_param("sssssssi", $date, $name, $kup, $izras, $ost, $prise, $itogo, $id);

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'mater change successfully!']);
} else {
    sendJson(['error' => true, 'message' => 'failed: ' . $conn->error]);
}
$stmt->close();
$conn->close();
?>