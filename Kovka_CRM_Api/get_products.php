<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('APP_START', true);
require_once 'config.php';
require_once ROOT_DIR . 'security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => true, 'message' => 'Method not allowed']);
    exit;
}

include ROOT_DIR . "db_connection.php";

$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';
if ($cod === '') {
    http_response_code(403);
    echo json_encode(['error' => true, 'message' => 'Forbidden: empty cod']);
    exit;
}

// Проверка через password_verify
$isAuth = false;
$r = mysqli_query($conn, "SELECT `cod` FROM `dostup`");
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        if (password_verify($cod, $row['cod'])) {
            $isAuth = true;
            break;
        }
    }
}

if (!$isAuth) {
    http_response_code(403);
    echo json_encode(['error' => true, 'message' => 'Forbidden: invalid cod']);
    exit;
}

$tables = [
    'mangal'   => ['folder' => 'Мангалы',    'label' => 'Мангалы'],
    'lavo4ki'  => ['folder' => 'Лавочки',    'label' => 'Лавочки'],
    'kozirek'  => ['folder' => 'Навесы',     'label' => 'Козырьки'],
    'zabor'    => ['folder' => 'Заборы',     'label' => 'Заборы'],
    'vorota'   => ['folder' => 'Ворота',     'label' => 'Ворота'],
    'ogradki'  => ['folder' => 'Оградки',    'label' => 'Оградки'],
    'reshetki' => ['folder' => 'Решетки',    'label' => 'Решётки'],
    'mebel'    => ['folder' => 'Мебель',     'label' => 'Мебель'],
    'melo4i'   => ['folder' => 'Мелочи',     'label' => 'Мелочи']
];

$products = [];

foreach ($tables as $table => $info) {
    $sql = "SELECT id, izdelie, image, Prise FROM `$table`";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imageFile = basename($row['image']);

            // ИСПОЛЬЗУЕМ КОНСТАНТУ ИЗ CONFIG.PHP
            $imageUrl = IMG_BASE . rawurlencode($imageFile);

            $products[] = [
                'id'       => $row['id'],
                'image'    => $row['image'],
                'tags'     => $row['izdelie'],
                'path'     => $imageUrl,
                'price'    => $row['Prise'],
                'category' => $table
            ];
        }
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($products, JSON_UNESCAPED_UNICODE);
