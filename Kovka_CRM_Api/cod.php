<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

include "../db_connection.php";

$response = array();
$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';

if ($cod === '') {
    echo json_encode(array("error" => true, "message" => "Insufficient parameters"));
    exit;
}

$r = mysqli_query($conn, "SELECT `id`, `cod` FROM `dostup`");
$userId = null;
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($cod, $row['cod'])) { $userId = (int)$row['id']; break; }
    }
}
if ($userId === null) {
    http_response_code(403);
    echo json_encode(array("error" => true, "message" => "Forbidden"));
    exit;
}

$stmt = mysqli_prepare($conn, "INSERT INTO `cod`(`user_id`) VALUES (?)");
mysqli_stmt_bind_param($stmt, "i", $userId);
if (mysqli_stmt_execute($stmt)) {
    $response['error'] = false;
    $response['message'] = "login logged successfully!";
} else {
    $response['error'] = true;
    $response['message'] = "failed: " . $conn->error;
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
echo json_encode($response);
?>