<?php
/**
 * Централизованные функции безопасности для проекта "Кованые изделия"
 * Подключать в начале каждого скрипта.
 */

// Запускаем сессию, если её нет
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- HTTP-заголовки безопасности (защита от XSS, кликджекинга, MIME-сниффинга) ---
function security_headers() {
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    // Content-Security-Policy (базовая) – разрешаем свои стили, скрипты, картинки
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://ajax.googleapis.com; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");
}

// --- Защита от XSS (экранирование вывода) ---
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// --- CSRF защита (токен для форм) ---
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_token_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function csrf_check() {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die('CSRF token validation failed. Possible cross-site request forgery.');
    }
}

// --- Аутентификация и авторизация (пример для вашей системы) ---
// Предполагается, что после логина в сессии сохраняются user_id и role
function require_login() {
    if (empty($_SESSION['user_id'])) {
        redirect('/login.php');
    }
}

function require_role($role) {
    require_login();
    if ($_SESSION['role'] !== $role) {
        http_response_code(403);
        die('Access denied');
    }
}

// --- Удобный редирект ---
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// --- Безопасное получение параметров из GET/POST ---
function get_int($key, $default = 0, $method = 'GET') {
    $source = ($method === 'GET') ? $_GET : $_POST;
    if (isset($source[$key]) && is_numeric($source[$key])) {
        return (int)$source[$key];
    }
    return $default;
}

function get_string($key, $default = '', $method = 'GET') {
    $source = ($method === 'GET') ? $_GET : $_POST;
    if (isset($source[$key])) {
        return trim($source[$key]);
    }
    return $default;
}

// --- Защита от path traversal для имён файлов ---
function safe_filename($filename) {
    return basename($filename);
}

// --- Проверка, что запрос является POST (для предотвращения случайных GET запросов к изменяющим данные скриптам) ---
function require_post() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die('Method not allowed');
    }
}

// --- Вспомогательная функция для экранирования в SQL (только если вы не используете подготовленные запросы) ---
function db_escape($conn, $string) {
    return $conn->real_escape_string($string);
}