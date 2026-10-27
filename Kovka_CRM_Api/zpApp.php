<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

// ИЗМЕНЕНО: Теперь сервер принимает только POST-запросы
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

include "../db_connection.php";

// ИЗМЕНЕНО: Получаем код авторизации из POST
$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';
if ($cod === '') {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("error" => true, "message" => "Forbidden"), JSON_UNESCAPED_UNICODE);
    exit;
}

// ИЗМЕНЕНО: Проверяем, существует ли такой код в БД
$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? LIMIT 1");
$stmt->bind_param("s", $cod);
$stmt->execute();
$res = $stmt->get_result();
$isAuth = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAuth) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array("error" => true, "message" => "Forbidden"), JSON_UNESCAPED_UNICODE);
    exit;
}

$sql_query = "SELECT * FROM `zp`";

$r = mysqli_query($conn, $sql_query);
$data = array();

if (mysqli_num_rows($r) > 0)
{
while($row = mysqli_fetch_assoc($r))
$data[] = $row;
}

// ИЗМЕНЕНО: Ключ ответа теперь "zp" (было "mater"), чтобы Android читал правильно
echo (json_encode(array("zp" => $data),JSON_UNESCAPED_UNICODE));
?>