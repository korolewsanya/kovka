<?php
define('APP_START', true);
require_once '../../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

require_once '../../db_connection.php';

$adminCod = isset($_POST['admin_cod']) ? trim($_POST['admin_cod']) : '';
if ($adminCod === '') {
    http_response_code(403);
    echo json_encode(['error' => true, 'message' => 'Forbidden']);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? AND class_work IN (1, 2) LIMIT 1");
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

$allowedTables = [
    'mangal', 'lavo4ki', 'kozirek', 'zabor',
    'vorota', 'ogradki', 'reshetki', 'mebel', 'melo4i'
];

if (empty($_POST['id']) || empty($_POST['category'])) {
    echo json_encode(['error' => true, 'message' => 'Необходимы параметры id и category'], JSON_UNESCAPED_UNICODE);
    exit;
}

$id       = (int)$_POST['id'];
$category = $_POST['category'];

if (!in_array($category, $allowedTables)) {
    echo json_encode(['error' => true, 'message' => "Недопустимая категория: $category"], JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "DELETE FROM `$category` WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => true, 'message' => 'Ошибка подготовки запроса: ' . $conn->error], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response = ['error' => false, 'message' => 'Товар удалён'];
    } else {
        $response = ['error' => false, 'message' => 'Запись не найдена или уже удалена'];
    }
} else {
    $response = ['error' => true, 'message' => 'Ошибка выполнения: ' . $stmt->error];
}

$stmt->close();
$conn->close();
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>