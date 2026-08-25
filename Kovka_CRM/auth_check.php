<?php
// auth_check.php - проверка авторизации для всех защищённых файлов

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Если это демо-режим - пропускаем без проверки
if (!empty($_SESSION['demo_mode'])) {
    return;
}

// Проверка: пользователь авторизован?
if (empty($_SESSION['user_logged_in'])) {
    header("Location: vhod.php");
    exit;
}

// Проверка роли
function check_role($required_role = null) {
    if (!empty($_SESSION['demo_mode'])) {
        if ($required_role === 'admin') {
            return 'demo_guest';
        }
        return 'demo_guest';
    }
    
    $user_role = $_SESSION['user_role'] ?? 'guest';
    
    if ($required_role && $user_role !== $required_role) {
        http_response_code(403);
        die('Доступ запрещён. Недостаточно прав.');
    }
    
    return $user_role;
}