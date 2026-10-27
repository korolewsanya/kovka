<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("error" => true, "message" => "Method not allowed"));
    exit;
}

include "../db_connection.php";

$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';
if ($cod === '') {
    http_response_code(403);
    echo json_encode(array("error" => true, "message" => "Forbidden"));
    exit;
}

// Проверяем, что код существует (любая роль, т.к. материалы смотрят и админы, и менеджеры)
$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? LIMIT 1");
$stmt->bind_param("s", $cod);
$stmt->execute();
$res = $stmt->get_result();
$isAuth = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAuth) {
    http_response_code(403);
    echo json_encode(array("error" => true, "message" => "Forbidden"));
    exit;
}

$sql_query = "SELECT * FROM `mater`";
$r = mysqli_query($conn, $sql_query);
$data = array();

if ($r && mysqli_num_rows($r) > 0) {
    while($row = mysqli_fetch_assoc($r)) {
        $data[] = $row;
    }
}
echo json_encode(array("mater" => $data), JSON_UNESCAPED_UNICODE);
mysqli_close($conn);
?>