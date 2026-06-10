<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$filename = $_POST['filename'] ?? '';
$filepath = 'uploads/' . $filename;

if (file_exists($filepath)) {
    if (unlink($filepath)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Cannot delete file']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'File not found']);
}
?>