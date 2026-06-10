<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

include "../db_connection.php";

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
            $imageFile = basename($row['image']); // только имя файла
            $imageUrl = $baseUrl . rawurlencode($imageFile);
            $products[] = [
                'id'       => $row['id'],
                'image'    => $row['image'],
                'tags'     => $row['izdelie'], // название товара
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