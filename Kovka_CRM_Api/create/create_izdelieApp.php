<?php
define('APP_START', true);
require_once '../../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => true, 'message' => 'Method not allowed']);
    exit;
}

require_once '../../db_connection.php';

$adminCod = isset($_POST['admin_cod']) ? trim($_POST['admin_cod']) : '';
if ($adminCod === '') {
    http_response_code(403);
    echo json_encode(['error' => true, 'message' => 'Forbidden']);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? AND class_work = 1 LIMIT 1");
$stmt->bind_param("s", $adminCod);
$stmt->execute();
$res = $stmt->get_result();
$isAdmin = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAdmin) {
    http_response_code(403);
    echo json_encode(['error' => true, 'message' => 'Forbidden']);
    exit;
}

$response = ['error' => true, 'message' => 'Недостаточно параметров'];

if (isset($_POST['table'], $_POST['izdelie'], $_POST['image'])) {
    $table = $_POST['table'];
    $izdelie = $_POST['izdelie'];
    $image = $_POST['image'];
    $dlina = $_POST['dlina'] ?? '';
    $shirina = $_POST['shirina'] ?? '';
    $visota = $_POST['visota'] ?? '';
    $prise = (float)($_POST['prise'] ?? 0);
    
    $allowedTables = ['mangal', 'lavo4ki', 'kozirek', 'zabor', 'vorota', 'ogradki', 'reshetki', 'mebel', 'melo4i'];
    if (!in_array($table, $allowedTables)) {
        echo json_encode(['error' => true, 'message' => 'Неверная таблица']);
        exit;
    }
    
    $stmt = $conn->prepare("INSERT INTO `$table` (izdelie, image, Dlina, Shirina, Visota, Prise) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $izdelie, $image, $dlina, $shirina, $visota, $prise);
    if ($stmt->execute()) {
        $response = ['error' => false, 'message' => 'Успешно добавлено'];
    } else {
        $response['message'] = 'Ошибка БД: ' . $stmt->error;
    }
    $stmt->close();
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
?>