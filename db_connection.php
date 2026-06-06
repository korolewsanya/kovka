<?php
$conn = new mysqli("localhost", "root", "", "kovka");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
// Установка кодировки (современный способ)
$conn->set_charset("utf8"); // вместо mysqli_query($con, "set names utf8")
