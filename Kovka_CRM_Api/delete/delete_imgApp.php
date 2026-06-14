<?php
define('APP_START', true);
require_once '../../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

// csrf_check(); // если используете

require_once '../../db_connection.php';   // или podklDB_images.php, смотря где таблицы

$response = [];

// Разрешённые таблицы (категории)
$allowedTables = [
    'mangal', 'lavo4ki', 'kozirek', 'zabor',
    'vorota', 'ogradki', 'reshetki', 'mebel', 'melo4i'
];

// Проверяем наличие обязательных параметров
if (empty($_POST['id']) || empty($_POST['category'])) {
    $response['error'] = true;
    $response['message'] = 'Необходимы параметры id и category';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$id       = (int)$_POST['id'];
$category = $_POST['category'];

// Белый список
if (!in_array($category, $allowedTables)) {
    $response['error'] = true;
    $response['message'] = "Недопустимая категория: $category";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// Формируем запрос (имя таблицы подставляем напрямую, т.к. оно проверено)
$sql = "DELETE FROM `$category` WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $response['error'] = true;
    $response['message'] = 'Ошибка подготовки запроса: ' . $conn->error;
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response['error'] = false;
        $response['message'] = 'Товар удалён';
    } else {
        $response['error'] = false;
        $response['message'] = 'Запись не найдена или уже удалена';
    }
} else {
    $response['error'] = true;
    $response['message'] = 'Ошибка выполнения: ' . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response, JSON_UNESCAPED_UNICODE);