<?php
// Защита от прямого доступа к файлу 
defined('ALLOWED') or die('Direct access not allowed');
$conn = new mysqli("localhost", "root", "", "kovka");

// Проверка подключения
if ($conn->connect_error) {
    // Логируем ошибку вместо вывода на экран (безопаснее)
    error_log("Database connection failed: " . $conn->connect_error);
    die("Извините, технические неполадки. Попробуйте позже.");
}

// Установка кодировки
$conn->set_charset("utf8"); 
