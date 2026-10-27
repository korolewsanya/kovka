<?php
define('APP_START', true);
require_once '../../security.php';
security_headers();

// ИЗМЕНЕНО: Проверка метода POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

require_once '../../db_connection.php';

$response = array();

// ИЗМЕНЕНО: Добавлена проверка admin_cod
$adminCod = isset($_POST['admin_cod']) ? trim($_POST['admin_cod']) : '';
if ($adminCod === '') {
    http_response_code(403);
    echo json_encode(array("error" => true, "message" => "Forbidden: empty admin_cod"));
    exit;
}

// ИЗМЕНЕНО: Проверка, что это админ
$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? AND class_work = 1 LIMIT 1");
$stmt->bind_param("s", $adminCod);
$stmt->execute();
$res = $stmt->get_result();
$isAdmin = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAdmin) {
    http_response_code(403);
    echo json_encode(array("error" => true, "message" => "Forbidden: not admin"));
    exit;
}

// ИЗМЕНЕНО: Добавлена строгая проверка на существование id
if(isset($_POST['id']) && $_POST['id'] !== ''){
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM zp WHERE id = ?");
    $stmt->bind_param("s",$id);
    
    if($stmt->execute()) {
        $response['error'] = false;
        $response['message'] = "deleted successfully!";
    } else {
        $response['error'] = true;
        $response['message'] = "failed\n ".$conn->error;
    }
    $stmt->close();
} else{
    $response['error'] = true;
    $response['message'] = "Insufficient Parameters";
}
echo json_encode($response);
?>