<?php
require_once '../security.php';

// Завершаем сессию
session_destroy();

// Перенаправляем на страницу входа
header("Location: vhod.php");
exit;