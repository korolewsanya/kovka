<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

include "../db_connection.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array("error" => "Method Not Allowed"), JSON_UNESCAPED_UNICODE);
    exit;
}

$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';

if ($cod === '') {
    echo json_encode(array("success" => false, "error" => "empty_code"), JSON_UNESCAPED_UNICODE);
    exit;
}

// ==========================================
// ЗАЩИТА ОТ ПЕРЕБОРА (Rate Limiting)
// ==========================================
$ip = $_SERVER['REMOTE_ADDR'];

// НАСТРОЙКИ ЛИМИТОВ
$maxAttempts = 5;       // Максимум попыток
$lockoutMinutes = 60;   // Блокировка (в минутах)

// Получаем данные о попытках с этого IP
$stmt = $conn->prepare("SELECT attempts, last_attempt FROM login_attempts WHERE ip_address = ?");
$stmt->bind_param("s", $ip);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($row) {
    // Считаем, сколько секунд прошло с последней попытки (внутри MySQL, без проблем с часовыми поясами)
    $stmt = $conn->prepare("SELECT TIMESTAMPDIFF(SECOND, last_attempt, NOW()) AS seconds_passed FROM login_attempts WHERE ip_address = ?");
    $stmt->bind_param("s", $ip);
    $stmt->execute();
    $res = $stmt->get_result();
    $timeRow = $res->fetch_assoc();
    $stmt->close();

    $timePassed = (int)$timeRow['seconds_passed'];

    // Если превысили лимит и время блокировки еще не вышло
    if ($row['attempts'] >= $maxAttempts && $timePassed < ($lockoutMinutes * 60)) {
        $remaining = (int)ceil(($lockoutMinutes * 60 - $timePassed) / 60);
        if ($remaining < 1) $remaining = 1;
        echo json_encode(array(
            "success" => false,
            "error" => "too_many_attempts",
            "message" => "Слишком много попыток. Подождите $remaining мин."
        ), JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Если время вышло, сбрасываем счетчик
    if ($timePassed >= ($lockoutMinutes * 60)) {
        $stmt = $conn->prepare("UPDATE login_attempts SET attempts = 0 WHERE ip_address = ?");
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $stmt->close();
        $row['attempts'] = 0;
    }
}

// ==========================================
// ПРОВЕРКА КОДА ДОСТУПА
// ==========================================
$r = mysqli_query($conn, "SELECT `id`, `class_work`, `prof`, `name`, `cod` FROM `dostup`");
$found = null;

if ($r) {
    while ($user = mysqli_fetch_assoc($r)) {
        if (password_verify($cod, $user['cod'])) {
            $found = $user;
            break;
        }
    }
}

if ($found) {
    // УСПЕХ: сбрасываем счетчик попыток
    $stmt = $conn->prepare("UPDATE login_attempts SET attempts = 0, last_attempt = NOW() WHERE ip_address = ?");
    $stmt->bind_param("s", $ip);
    $stmt->execute();
    $stmt->close();

    echo json_encode(array(
        "success" => true,
        "user" => array(
            "id"         => (int)$found['id'],
            "class_work" => (int)$found['class_work'],
            "prof"       => $found['prof'],
            "name"       => $found['name']
        )
    ), JSON_UNESCAPED_UNICODE);
} else {
    // ОШИБКА: увеличиваем счетчик попыток
    if ($row) {
        $stmt = $conn->prepare("UPDATE login_attempts SET attempts = attempts + 1, last_attempt = NOW() WHERE ip_address = ?");
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO login_attempts (ip_address, attempts, last_attempt) VALUES (?, 1, NOW())");
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $stmt->close();
    }

    usleep(500000); // 0.5 сек задержки, чтобы замедлить ботов

    echo json_encode(array("success" => false, "error" => "invalid_code"), JSON_UNESCAPED_UNICODE);
}

mysqli_close($conn);
?>