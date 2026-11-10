<?php
session_start();

require_once '../security.php';
security_headers();

include "../db_connection.php";

$error_message = '';

// ==========================================
// НАСТРОЙКИ ЗАЩИТЫ ОТ ПЕРЕБОРА
// ==========================================
$maxAttempts = 2;       // Максимум попыток
$lockoutMinutes = 1;   // Блокировка в минутах
$ip = $_SERVER['REMOTE_ADDR'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    if (!isset($_POST['agree_rules']) || $_POST['agree_rules'] !== 'on') {
        $error_message = 'Для входа вы должны согласиться с правилами тестирования и политикой конфиденциальности.';
    } else {
        // ==========================================
        // ПРОВЕРКА БЛОКИРОВКИ IP
        // ==========================================
        $stmt = $conn->prepare("SELECT attempts, last_attempt FROM login_attempts WHERE ip_address = ?");
        $stmt->bind_param("s", $ip);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        $isBlocked = false;
        if ($row) {
            $stmt = $conn->prepare("SELECT TIMESTAMPDIFF(SECOND, last_attempt, NOW()) AS seconds_passed FROM login_attempts WHERE ip_address = ?");
            $stmt->bind_param("s", $ip);
            $stmt->execute();
            $res = $stmt->get_result();
            $timeRow = $res->fetch_assoc();
            $stmt->close();

            $timePassed = (int)$timeRow['seconds_passed'];

            if ($row['attempts'] >= $maxAttempts && $timePassed < ($lockoutMinutes * 60)) {
                $remaining = (int)ceil(($lockoutMinutes * 60 - $timePassed) / 60);
                if ($remaining < 1) $remaining = 1;
                $error_message = "Слишком много попыток. Подождите $remaining мин.";
                $isBlocked = true;
            }

            if (!$isBlocked && $timePassed >= ($lockoutMinutes * 60)) {
                $stmt = $conn->prepare("UPDATE login_attempts SET attempts = 0 WHERE ip_address = ?");
                $stmt->bind_param("s", $ip);
                $stmt->execute();
                $stmt->close();
                $row['attempts'] = 0;
            }
        }

        if (!$isBlocked) {
            $nabor = trim($_POST['nabor'] ?? '');

            if (!empty($nabor)) {
                // ==========================================
                // ИЩЕМ ПОЛЬЗОВАТЕЛЯ В DOSTUP ПО ХЕШУ
                // ==========================================
                $found = null;
                $r = mysqli_query($conn, "SELECT `id`, `class_work`, `prof`, `name`, `cod` FROM `dostup`");
                if ($r) {
                    while ($user = mysqli_fetch_assoc($r)) {
                        // Сверяем введенный код с хешем
                        if (password_verify($nabor, $user['cod'])) {
                            $found = $user;
                            break;
                        }
                    }
                }

                if ($found) {
                    // УСПЕХ: сбрасываем счетчик
                    $stmt = $conn->prepare("UPDATE login_attempts SET attempts = 0, last_attempt = NOW() WHERE ip_address = ?");
                    $stmt->bind_param("s", $ip);
                    $stmt->execute();
                    $stmt->close();

                    $class_work = $found['class_work'];

                    $roles = [
                        1 => 'admin',
                        2 => 'diz',
                        3 => 'svar',
                        4 => 'slesar',
                        5 => 'color',
                        6 => 'car'
                    ];

                    $role = null;
                    if (isset($roles[$class_work])) {
                        $role = $roles[$class_work];
                    } else {
                        // Определяем роль по prof
                        $profLower = mb_strtolower($found['prof'], 'UTF-8');
                        if (mb_strpos($profLower, 'админ') !== false) $role = 'admin';
                        elseif (mb_strpos($profLower, 'дизайн') !== false) $role = 'diz';
                        elseif (mb_strpos($profLower, 'свар') !== false) $role = 'svar';
                        elseif (mb_strpos($profLower, 'слесар') !== false) $role = 'slesar';
                        elseif (mb_strpos($profLower, 'маляр') !== false) $role = 'color';
                        elseif (mb_strpos($profLower, 'водит') !== false || mb_strpos($profLower, 'груз') !== false) $role = 'car';
                    }

                    if ($role) {
                        $_SESSION['user_logged_in'] = true;
                        $_SESSION['user_role'] = $role;
                        $_SESSION['user_cod'] = $nabor;
                        $_SESSION['agreed_to_rules'] = true;
                        $_SESSION['agreement_time'] = date('Y-m-d H:i:s');

                        // Обновляем последний код (если нужно)
                        $updateStmt = $conn->prepare("UPDATE cod SET cod = ? WHERE id = 6");
                        $updateStmt->bind_param("s", $nabor);
                        $updateStmt->execute();
                        $updateStmt->close();

                        if ($role === 'admin') {
                            header("Location: admin.php");
                        } else {
                            header("Location: worker.php");
                        }
                        exit;
                    } else {
                        $error_message = 'Роль не определена. Обратитесь к администратору.';
                    }
                } else {
                    // ОШИБКА: увеличиваем счетчик
                    if ($row) {
                        $stmt = $conn->prepare("UPDATE login_attempts SET attempts = attempts + 1, last_attempt = NOW() WHERE ip_address = ?");
                        $stmt->bind_param("s", $ip);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        $stmt = $conn->prepare("INSERT INTO login_attempts (ip_address, attempts, last_attempt) VALUES (?, 1, NOW())");
                        $stmt->bind_param("s", $ip);
                        $stmt->execute();
                        $stmt->close();
                    }

                    $error_message = 'Неверный код доступа';
                }
            } else {
                $error_message = 'Введите код доступа';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Вход</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif; background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); min-height:100vh; display:flex; justify-content:center; align-items:center; }
    .login-container { background:white; border-radius:20px; box-shadow:0 20px 40px rgba(0,0,0,0.2); padding:40px; width:350px; text-align:center; }
    h1 { color:#333; margin-bottom:30px; }
    input[type="text"] { width:100%; padding:15px; font-size:18px; text-align:center; border:2px solid #ddd; border-radius:50px; outline:none; }
    input[type="text"]:focus { border-color:#667eea; }
    input[type="submit"] { width:100%; padding:12px; background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:white; border:none; border-radius:50px; font-size:18px; font-weight:bold; cursor:pointer; margin-top:20px; }
    .error { background:#f44336; color:white; padding:10px; border-radius:10px; margin-bottom:20px; font-size:14px; }
    .agree-block { margin-top: 20px; text-align: left; font-size: 13px; color: #555; line-height: 1.4; }
    .agree-block label { display: flex; align-items: flex-start; gap: 8px; cursor: pointer; }
    .agree-block input[type="checkbox"] { margin-top: 3px; width: 16px; height: 16px; flex-shrink: 0; cursor: pointer; }
    .agree-block a { color: #667eea; text-decoration: none; }
    .agree-block a:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="login-container">
    <h1>Вход в систему</h1>
    <?php if (!empty($error_message)): ?>
        <div class="error"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>
    <form method="POST">
        <?= csrf_token_field() ?>
        <input type="text" name="nabor" placeholder="Введите код доступа" autofocus autocomplete="off">

        <div class="agree-block">
            <label>
                <input type="checkbox" name="agree_rules" required>
                <span>
                    Соглашаюсь с
                    <a href="privacy.php" target="_blank">политикой конфиденциальности</a>
                    и правилами тестирования.
                </span>
            </label>
        </div>

        <input type="submit" value="Войти">
    </form>
</div>
</body>
</html>