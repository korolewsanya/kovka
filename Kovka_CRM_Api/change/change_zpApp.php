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

// ИЗМЕНЕНО: Заменил $_POST['...'] на безопасную проверку isset()
$id = isset($_POST['id']) ? $_POST['id'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';
$spec = isset($_POST['spec']) ? $_POST['spec'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$nachis = isset($_POST['nachis']) ? $_POST['nachis'] : '';
$poluch = isset($_POST['poluch']) ? $_POST['poluch'] : '';

// ИЗМЕНЕНО: Более строгая проверка на пустые поля
if ($id === '' || $date === '' || $spec === '' || $name === '' || $nachis === '' || $poluch === '') {
    $response['error'] = true;
    $response['message'] = "Insufficient parameters";
} else {
    $stmt = $conn->prepare("UPDATE zp SET date = ?, spec = ?, name = ?, nachis = ?,poluch = ? WHERE id = ?");
    $stmt->bind_param("sssssi",$date,$spec,$name,$nachis,$poluch,$id);

    if($stmt->execute() == TRUE){
        $response['error'] = false;
        $response['message'] = "zp change successfully!";
    } else{
        $response['error'] = true;
        $response['message'] = "failed\n ".$conn->error;
    }
    $stmt->close();
}
echo json_encode($response);
?>