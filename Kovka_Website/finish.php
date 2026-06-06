<?php
require_once '../security.php';
security_headers();
require_post();
csrf_check();

include '../db_connection.php';


// Инициализация переменной успеха
$success = false;
$error_message = '';
$izdelie = '';
$prise = 0;
$name = '';
$tel = '';
$email = '';

// Проверка наличия всех необходимых полей через isset()
if (isset($_POST['data'], $_POST['izdelie'], $_POST['image'], $_POST['dlina'], 
          $_POST['shirina'], $_POST['visota'], $_POST['prise'], 
          $_POST['name'], $_POST['tel'], $_POST['email'], $_POST['coment'])) {
    
    $data = $_POST['data'];
    $izdelie = $_POST['izdelie'];
    
    // basename() уже безопасен, добавим валидацию допустимых символов
    $image_raw = basename($_POST['image']);
    // Разрешаем только буквы, цифры, точки, дефисы и подчёркивания
    if (preg_match('/^[a-zA-Z0-9._-]+$/', $image_raw)) {
        $image = $image_raw;
    } else {
        $image = '';
        $error_message = 'Недопустимое имя файла';
    }
    
    $dlina = $_POST['dlina'];
    $shirina = $_POST['shirina'];
    $visota = $_POST['visota'];
    $prise = (float)$_POST['prise'];  // явное приведение к числу
    $name = $_POST['name'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $coment = $_POST['coment'];
    
    // Подготовка запроса с проверкой результата
    $stmt = $conn->prepare("INSERT INTO `zakaz` 
        (`date`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`, `Name`, `Tel`, `Email`, `Coment`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        // Логирование ошибки prepare (без вывода пользователю)
        error_log("Ошибка prepare: " . $conn->error);
        $error_message = 'Внутренняя ошибка сервера';
    } else {
        // Правильные типы: ssssss d ssss (6 строк, 1 double, 4 строки)
        $stmt->bind_param("ssssssdssss", 
            $data, $izdelie, $image, $dlina, $shirina, $visota, 
            $prise, $name, $tel, $email, $coment);
        
        if ($stmt->execute()) {
            $success = true;
        } else {
            // Логирование ошибки выполнения
            error_log("Ошибка execute: " . $stmt->error);
            $error_message = 'Ошибка сохранения заказа';
        }
        $stmt->close();
    }
    $conn->close();
} else {
    $error_message = 'Не все поля заполнены';
    // Для отладки на локали (можно закомментировать)
    error_log("Отсутствуют поля POST: " . print_r($_POST, true));
}

// Если произошла ошибка, но $success не установлен
if (!$success && empty($error_message)) {
    $error_message = 'Неизвестная ошибка';
}

// Используем полученные данные для вывода (если нужно)
$izdelie = $izdelie ?? 'Неизвестное изделие';
$prise = $prise ?? 0;
$name = $name ?? '';
$tel = $tel ?? '';
$email = $email ?? '';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результат оформления заказа | Кованые изделия</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif; background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); min-height:100vh; display:flex; justify-content:center; align-items:center; padding:20px; }
        .result-card { background:white; border-radius:20px; box-shadow:0 20px 40px rgba(0,0,0,0.2); max-width:500px; width:100%; padding:40px 30px; text-align:center; animation:fadeInUp 0.5s ease-out; }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        .icon { width:80px; height:80px; background:#4CAF50; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 25px; }
        .icon svg { width:50px; height:50px; fill:white; }
        .error-icon { background:#f44336; }
        h1 { font-size:28px; color:#333; margin-bottom:15px; }
        .message { font-size:18px; color:#555; line-height:1.5; margin-bottom:30px; }
        .details { background:#f8f9fa; border-radius:12px; padding:15px; margin-bottom:30px; text-align:left; font-size:14px; }
        .details p { margin:8px 0; color:#444; }
        .details strong { color:#333; }
        .button { display:inline-block; background:#667eea; color:white; text-decoration:none; padding:12px 30px; border-radius:40px; font-weight:600; transition:background 0.3s ease; cursor:pointer; font-size:16px; border:none; }
        .button:hover { background:#5a67d8; transform:translateY(-2px); }
    </style>
</head>
<body>
    <div class="result-card">
        <?php if ($success): ?>
            <div class="icon">
                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
            </div>
            <h1>Заказ успешно оформлен!</h1>
            <div class="message">
                <p>Спасибо за ваш заказ!</p>
                <p>В ближайшее время наш менеджер свяжется с вами для уточнения деталей.</p>
            </div>
            <div class="details">
                <p><strong>Изделие:</strong> <?= htmlspecialchars($izdelie) ?></p>
                <p><strong>Стоимость:</strong> <?= number_format($prise, 0, '.', ' ') ?> ₽</p>
                <p><strong>Контактное лицо:</strong> <?= htmlspecialchars($name) ?></p>
                <p><strong>Телефон:</strong> <?= htmlspecialchars($tel) ?></p>
                <?php if (!empty($email)): ?>
                    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
                <?php endif; ?>
            </div>
            <a href="glav.php" class="button">Вернуться на главную</a>
        <?php else: ?>
            <div class="icon error-icon">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </div>
            <h1>Ошибка оформления</h1>
            <div class="message error-message">
                <p>Не удалось сохранить заказ. Попробуйте ещё раз или свяжитесь с нами по телефону.</p>
                <p>Приносим извинения за временные неудобства.</p>
                <?php if (!empty($error_message) && defined('DEBUG') && DEBUG === true): ?>
                    <p style="font-size:12px; color:#999;"><?= htmlspecialchars($error_message) ?></p>
                <?php endif; ?>
            </div>
            <a href="javascript:history.back()" class="button">Вернуться назад</a>
        <?php endif; ?>
    </div>
</body>
</html>