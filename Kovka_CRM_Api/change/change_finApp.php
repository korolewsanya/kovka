<?php

error_reporting(0);
ini_set('display_errors', 0);
ob_start();

define('APP_START', true);
require_once '../../security.php';
security_headers();

function sendJson($data)
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJson(['error' => true, 'message' => 'Method not allowed']);
}

include "../../db_connection.php";

$adminCod = isset($_POST['cod']) ? trim($_POST['cod']) : '';
$adminCod = isset($_POST['cod']) ? trim($_POST['cod']) : '';
if ($adminCod === '') {
    sendJson(['error' => true, 'message' => 'Forbidden: empty admin_cod']);
}

$isAdmin = false;
$r = mysqli_query($conn, "SELECT `cod` FROM `dostup` WHERE `class_work` IN (1, 4)");
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

$id     = isset($_POST['id']) ? $_POST['id'] : '';
$date   = isset($_POST['date']) ? $_POST['date'] : '';
$dohod  = isset($_POST['dohod']) ? $_POST['dohod'] : '';
$rashod = isset($_POST['rashod']) ? $_POST['rashod'] : '';
$prib   = isset($_POST['prib']) ? $_POST['prib'] : '';

if ($id === '' || $date === '' || $dohod === '' || $rashod === '' || $prib === '') {
    sendJson(['error' => true, 'message' => 'Insufficient parameters']);
}

$stmt = $conn->prepare("UPDATE fin SET date = ?, dohod = ?, rashod = ?, prib = ? WHERE id = ?");
$stmt->bind_param("ssssi", $date, $dohod, $rashod, $prib, $id);

if ($stmt->execute()) {
    sendJson(['error' => false, 'message' => 'fin change successfully!']);
} else {
    sendJson(['error' => true, 'message' => 'failed: ' . $conn->error]);
}
$stmt->close();
$conn->close();
