<?php
// Проверка apicall
if (!isset($_GET['apicall']) || $_GET['apicall'] !== 'uploadpic') {
    sendResponse(true, 'Invalid API call');
    exit;
}

// Путь к папке для сохранения файлов (ЛОКАЛЬНЫЙ, НЕ HTTP)
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Kovka_git/kovka/img/';

// Создать папку, если её нет
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

function sendResponse($error, $message, $fileName = '', $fileUrl = '') {
    $response = [
        'error' => $error,
        'message' => $message
    ];
    if ($fileName) {
        $response['fileName'] = $fileName;
    }
    if ($fileUrl) {
        $response['fileUrl'] = $fileUrl;
    }
    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pic'])) {
    $file = $_FILES['pic'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $originalName = basename($file['name']);
        $newName = time() . '_' . $originalName;
        $destination = $uploadDir . $newName;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $fileUrl = 'http://192.168.1.156/Kovka_git/kovka/img/' . $newName;
            sendResponse(false, 'File uploaded successfully', $newName, $fileUrl);
        } else {
            sendResponse(true, 'Failed to move uploaded file');
        }
    } else {
        sendResponse(true, 'Upload error code: ' . $file['error']);
    }
} else {
    sendResponse(true, 'No file or invalid request method');
}
?>