<?php
session_start();  // создаёт или возобновляет сессию, чтобы сохранить данные пользователя (роль, код доступа) между разными страницами.

require_once '../security.php';
security_headers();

include "../db_connection.php";

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    
    $nabor = trim($_POST['nabor'] ?? '');
    
    if (!empty($nabor)) {
        $stmt = $conn->prepare("SELECT `class_work` FROM `otchet` WHERE `cod` = ?");
        $stmt->bind_param("s", $nabor);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if ($user) {
            $class_work = $user['class_work'];
            
            $roles = [
                1 => 'admin',
                2 => 'diz',
                3 => 'svar',
                4 => 'slesar',
                5 => 'color',
                6 => 'car'
            ];
            
            if (isset($roles[$class_work])) {
              
                // Сохраняем данные в сессию
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_role'] = $roles[$class_work];
                $_SESSION['user_cod'] = $nabor;
                
                // Обновляем последний код
                $updateStmt = $conn->prepare("UPDATE cod SET cod = ? WHERE id = 6");
                $updateStmt->bind_param("s", $nabor);
                $updateStmt->execute();
                $updateStmt->close();
                
                // Перенаправляем в зависимости от роли
                if ($roles[$class_work] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: worker.php");
                }
                exit;
            }
        }
    }
    $error_message = 'Неверный код доступа';
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
        <input type="text" name="nabor" placeholder="Введите код доступа" autofocus>
        <input type="submit" value="Войти">
    </form>
</div>
</body>
</html>