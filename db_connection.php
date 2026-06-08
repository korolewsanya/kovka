<?php

$conn = new mysqli("localhost", "root", "", "kovka");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
// Установка кодировки
$conn->set_charset("utf8"); 
