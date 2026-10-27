<?php
define('APP_START', true);
require_once '../../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => true, 'message' => 'Method not allowed']);
    exit;
}

require_once '../../db_connection.php';

if ($conn->connect_error) {
    echo json_encode(['error' => true, 'message' => 'DB connection failed'], JSON_UNESCAPED_UNICODE);
    exit;
}

$adminCod = isset($_POST['admin_cod']) ? trim($_POST['admin_cod']) : '';
if ($adminCod === '') {
    http_response_code(403);
    echo json_encode(['error' => true, 'message' => 'Forbidden']);
    exit;
}

// Проверка админа
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

if (empty($_POST['id']) || empty($_POST['category']) || !isset($_POST['image']) || !isset($_POST['izdelie'])) {
    echo json_encode(['error' => true, 'message' => 'Недостаточно параметров']);
    exit;
}

$id       = (int)$_POST['id'];
$category = $_POST['category'];
$image    = $_POST['image'];
$izdelie  = $_POST['izdelie'];

if (!in_array($category, $allowedTables)) {
    echo json_encode(['error' => true, 'message' => "Недопустимая категория: $category"]);
    exit;
}

$sql = "UPDATE `$category` SET image = ?, izdelie = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => true, 'message' => 'Ошибка подготовки запроса: ' . $conn->error]);
    exit;
}

$stmt->bind_param('ssi', $image, $izdelie, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response = ['error' => false, 'message' => 'Товар обновлён'];
    } else {
        $response = ['error' => false, 'message' => 'Запрос выполнен, но запись не изменена'];
    }
} else {
    $response = ['error' => true, 'message' => 'Ошибка выполнения: ' . $stmt->error];
}

$stmt->close();
$conn->close();
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>