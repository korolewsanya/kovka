<?php
// ВКЛЮЧИТЕ ТОЛЬКО ДЛЯ ОТЛАДКИ
//error_reporting(E_ALL);
//ini_set('display_errors', 0);       // не показываем на экран, будем возвращать в JSON
//ini_set('log_errors', 1);
//ini_set('error_log', __DIR__ . '/../logs/update_debug.log'); // папка logs должна существовать

define('APP_START', true);
require_once '../../security.php';
security_headers();

// Ловим фатальные ошибки
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(500);
        echo json_encode([
            'error' => true,
            'message' => 'Fatal error: ' . $error['message'] . ' in ' . $error['file'] . ':' . $error['line']
        ], JSON_UNESCAPED_UNICODE);
    }
});

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => true, 'message' => 'Method not allowed']);
    exit;
}

// csrf_check();  // если используете

require_once '../../db_connection.php';

// Проверка соединения
if ($conn->connect_error) {
    $response = ['error' => true, 'message' => 'DB connection failed: ' . $conn->connect_error];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$allowedTables = [
    'mangal', 'lavo4ki', 'kozirek', 'zabor',
    'vorota', 'ogradki', 'reshetki', 'mebel', 'melo4i'
];

// Логируем входящие данные (потом убрать)
//file_put_contents(__DIR__ . '/../logs/post_data.log', print_r($_POST, true), FILE_APPEND);

// Проверяем наличие параметров
if (empty($_POST['id']) || empty($_POST['category']) || !isset($_POST['image']) || !isset($_POST['izdelie'])) {
    $missing = [];
    if (empty($_POST['id'])) $missing[] = 'id';
    if (empty($_POST['category'])) $missing[] = 'category';
    if (!isset($_POST['image'])) $missing[] = 'image';
    if (!isset($_POST['izdelie'])) $missing[] = 'izdelie';
    $response = [
        'error' => true,
        'message' => 'Недостаточно параметров: ' . implode(', ', $missing)
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$id       = (int)$_POST['id'];
$category = $_POST['category'];
$image    = $_POST['image'];
$izdelie  = $_POST['izdelie'];

if (!in_array($category, $allowedTables)) {
    $response = [
        'error' => true,
        'message' => "Недопустимая категория: $category"
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "UPDATE `$category` SET image = ?, izdelie = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $response = [
        'error' => true,
        'message' => 'Ошибка подготовки запроса: ' . $conn->error//,
        //'sql' => $sql  // только на время отладки!
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt->bind_param('ssi', $image, $izdelie, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $response = ['error' => false, 'message' => 'Товар обновлён'];
    } else {
        // Запрос выполнен, но ни одна строка не изменена – возможно, id не найден
        $response = ['error' => false, 'message' => 'Запрос выполнен, но запись не изменена (возможно, id не существует или данные те же)'];
    }
} else {
    $response = ['error' => true, 'message' => 'Ошибка выполнения: ' . $stmt->error];
}

$stmt->close();
$conn->close();

echo json_encode($response, JSON_UNESCAPED_UNICODE);