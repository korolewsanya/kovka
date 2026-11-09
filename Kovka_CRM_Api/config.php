<?php

define('BASE_URL', 'http://192.168.1.156/Kovka_git/kovka/');

// Составные пути
define('API_BASE',  BASE_URL . 'Kovka_CRM_Api/');
define('SITE_BASE', BASE_URL . 'Kovka_Website/');
define('IMG_BASE',  BASE_URL . 'img/');

// константа для upload_image.php
define('UPLOAD_URL', IMG_BASE);

// Пути для создания, замены, удаления
define('URL_CREATE', API_BASE . 'create/');
define('URL_CHANGE', API_BASE . 'change/');
define('URL_DELETE', API_BASE . 'delete/');

// Физические пути
define('UPLOAD_DIR', dirname(__DIR__) . '/img/');
define('ROOT_DIR',   dirname(__DIR__) . '/');

// Относительный путь к картинкам для Android
define('RELATIVE_IMG_URL', '../img/');