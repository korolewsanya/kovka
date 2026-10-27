<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => true, 'message' => 'Method not allowed']);
    exit;
}

include "../db_connection.php";

$cod = isset($_POST['cod']) ? trim($_POST['cod']) : '';
if ($cod === '') {
    http_response_code(403);
    echo json_encode(['error' => true, 'message' => 'Forbidden']);
    exit;
}

// Проверка, что код существует (любая роль)
$stmt = $conn->prepare("SELECT id FROM dostup WHERE cod = ? LIMIT 1");
$stmt->bind_param("s", $cod);
$stmt->execute();
$res = $stmt->get_result();
$isAuth = ($res && $res->num_rows > 0);
$stmt->close();

if (!$isAuth) {
    http_response_code(403);
    echo json_encode(['error' => true, 'message' => 'Forbidden']);
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

$baseUrl = 'http://192.168.1.156/Kovka_git/kovka/img/';
$products = [];

foreach ($tables as $table => $info) {
    $folder = $info['folder'];
    $sql = "SELECT id, izdelie, image, Prise FROM `$table`";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $imageFile = basename($row['image']);
            $imageUrl = $baseUrl . rawurlencode($imageFile);
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
?>