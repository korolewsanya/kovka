<?php
define('APP_START', true);
require_once '../security.php';
require_once 'auth_check.php';
security_headers();

include "../db_connection.php";

$tables = [
    'mangal', 'lavo4ki', 'kozirek', 'zabor', 'vorota', 'ogradki', 'reshetki', 'mebel', 'melo4i'
];

if (isset($_GET['get_one']) && isset($_GET['table']) && isset($_GET['id'])) {
    $table = $_GET['table'];
    $id = (int)$_GET['id'];
    if (in_array($table, $tables)) {
        $stmt = $conn->prepare("SELECT * FROM `$table` WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'row' => $row]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Неверная таблица']);
    }
    exit;
}

$items = [];
foreach ($tables as $table) {
    $result = $conn->query("SELECT id, izdelie, image, Dlina, Shirina, Visota, Prise FROM `$table`");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['table'] = $table;
            $items[] = $row;
        }
    }
}

if (isset($_GET['table']) && $_GET['table'] !== 'all') {
    $filter = $_GET['table'];
    $items = array_filter($items, fn($item) => $item['table'] === $filter);
    $items = array_values($items);
}
echo json_encode(['items' => $items]);
?>