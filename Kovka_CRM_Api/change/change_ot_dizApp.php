<?php
define('APP_START', true);
require_once '../../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}
//csrf_check();

require_once '../../db_connection.php';

$response = array();

if (isset($_POST['id']) && isset($_POST['otchet']) && $_POST['id'] !== '' && $_POST['otchet'] !== '') {
    $id = intval($_POST['id']);
    $otchet = $_POST['otchet'];

    $stmt = $conn->prepare("UPDATE otchet SET otchet = ? WHERE id = ?");
    $stmt->bind_param("si", $otchet, $id);

    if ($stmt->execute() === TRUE) {
        $response['error'] = false;
        $response['message'] = "otchet change successfully!";
    } else {
        $response['error'] = true;
        $response['message'] = "failed\n " . $conn->error;
    }
} else {
    $response['error'] = true;
    $response['message'] = "Insufficient parameters";
}

echo json_encode($response);
?>
