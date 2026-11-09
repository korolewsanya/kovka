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
$r = mysqli_query($conn, "SELECT `cod` FROM `dostup` WHERE `class_work` IN (1, 2, 4)");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($adminCod, $row['cod'])) {
            $isAdmin = true;
            break;
        }
    }
}

if (!$isAdmin) {
    sendJson(['error' => true, 'message' => 'Forbidden: no access']);
}

$id       = isset($_POST['id'])       ? $_POST['id']       : '';
$data     = isset($_POST['date'])     ? $_POST['date']     : '';
$izdelie  = isset($_POST['izdelie'])  ? $_POST['izdelie']  : '';
$image    = isset($_POST['image'])    ? $_POST['image']    : '';
$dlina    = isset($_POST['dlina'])    ? $_POST['dlina']    : '';
$shirina  = isset($_POST['shirina'])  ? $_POST['shirina']  : '';
$visota   = isset($_POST['visota'])   ? $_POST['visota']   : '';
$prise    = isset($_POST['prise'])    ? $_POST['prise']    : '';
$pay      = isset($_POST['pay'])      ? $_POST['pay']      : '';
$proces   = isset($_POST['proces'])   ? $_POST['proces']   : '';
$name     = isset($_POST['name'])     ? $_POST['name']     : '';
$tel      = isset($_POST['tel'])      ? $_POST['tel']      : '';
$email    = isset($_POST['email'])    ? $_POST['email']    : '';
$coment   = isset($_POST['coment'])   ? $_POST['coment']   : '';

if ($id === '' || $izdelie === '' || $prise === '' || $pay === '' || $tel === '' || $data === '' || $email === '') {
    sendJson(['error' => true, 'message' => 'Insufficient parameters']);
}

$stmt = $conn->prepare("UPDATE zakaz SET date = ?, izdelie = ?, image = ?, Dlina = ?, Shirina = ?, Visota = ?, Prise = ?, Pay = ?, Proces = ?, Name = ?, Tel = ?, Email = ?, Coment = ? WHERE Id = ?");
$stmt->bind_param("sssssssssssssi", $data, $izdelie, $image, $dlina, $shirina, $visota, $prise, $pay, $proces, $name, $tel, $email, $coment, $id);

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'zakaz change successfully!']);
} else {
    sendJson(['error' => true, 'message' => 'failed: ' . $conn->error]);
}
$stmt->close();
$conn->close();
?>