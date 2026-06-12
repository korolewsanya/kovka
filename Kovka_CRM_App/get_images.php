<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$directory = '../img/';
$images = array_diff(scandir($directory), array('..', '.'));
$result = [];

foreach ($images as $image) {
    $result[] = [
        'name' => $image,
        'url' => $directory . $image
    ];
}

echo json_encode($result);
?>