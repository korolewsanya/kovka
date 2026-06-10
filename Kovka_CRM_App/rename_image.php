<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$oldName = $_POST['old_name'] ?? '';
$newName = $_POST['new_name'] ?? '';

// Безопасность: проверяем расширение
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$ext = pathinfo($newName, PATHINFO_EXTENSION);

if (!in_array(strtolower($ext), $allowedExtensions)) {
    echo json_encode(['success' => false, 'error' => 'Invalid file extension']);
    exit;
}

$oldPath = 'uploads/' . $oldName;
$newPath = 'uploads/' . $newName;

if (file_exists($oldPath)) {
    if (rename($oldPath, $newPath)) {
        echo json_encode(['success' => true, 'new_name' => $newName]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Cannot rename file']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'File not found']);
}
?>