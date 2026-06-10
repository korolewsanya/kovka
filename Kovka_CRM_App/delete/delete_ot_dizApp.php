<?php
// Убираем всё лишнее, оставляем минимальный рабочий код
header('Content-Type: application/json; charset=utf-8');

// Подключаем БД
$conn = new mysqli("localhost", "root", "", "kovka");
if ($conn->connect_error) {
    echo json_encode(['error' => true, 'message' => 'Ошибка БД: ' . $conn->connect_error], JSON_UNESCAPED_UNICODE);
    exit;
}
$conn->set_charset("utf8");

$response = ['error' => true, 'message' => 'Недостаточно параметров'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    
    $result = $conn->query("DELETE FROM otchet WHERE id = $id");
    
    if ($result === true && $conn->affected_rows > 0) {
        $response = ['error' => false, 'message' => 'Удалено'];
    } else {
        $response['message'] = 'Запись с id=' . $id . ' не найдена или не удалена';
    }
} else {
    $response['message'] = 'Метод не POST или нет параметра id';
}

$conn->close();
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>