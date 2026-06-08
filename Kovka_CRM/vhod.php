<?php
require_once '../security.php';
security_headers();

// Подключение к БД
include '../db_connection.php';

$nabor = "не определено";

if (isset($_POST["nabor"])) {
    csrf_check(); // Добавляем CSRF-защиту
    
    $nabor = $_POST["nabor"];
    
    // Защита от SQL-инъекций (подготовленный запрос)
    $stmt = $conn->prepare("SELECT `class_work` FROM `otchet` WHERE `cod` = ?");
    $stmt->bind_param("s", $nabor);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user) {
        $class_work = $user['class_work'];
        
        if ($class_work == 1) {
            $sql = "UPDATE cod SET cod = '$nabor' WHERE id = 6";
            $conn->query($sql);
            header("Location: worker.php?role=admin");
            exit;
        } else if ($class_work == 2) {
            $sql = "UPDATE cod SET cod = '$nabor' WHERE id = 6";
            $conn->query($sql);
            header("Location: worker.php?role=diz");
            exit;
        } else if ($class_work == 3) {
            $sql = "UPDATE cod SET cod = '$nabor' WHERE id = 6";
            $conn->query($sql);
            header("Location: worker.php?role=svar");
            exit;
        } else if ($class_work == 4) {
            $sql = "UPDATE cod SET cod = '$nabor' WHERE id = 6";
            $conn->query($sql);
            header("Location: worker.php?role=slesar");
            exit;
        } else if ($class_work == 5) {
            $sql = "UPDATE cod SET cod = '$nabor' WHERE id = 6";
            $conn->query($sql);
            header("Location: worker.php?role=color");
            exit;
        } else if ($class_work == 6) {
            $sql = "UPDATE cod SET cod = '$nabor' WHERE id = 6";
            $conn->query($sql);
            header("Location: worker.php?role=car");
            exit;
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
</head>
    <body style="text-align: center">
    
    <form method="POST">
    <?= csrf_token_field() ?> <!-- Добавляем CSRF-токен -->
<input id="nabor" type="text" name="nabor" style="text-align:center; margin-top: 80px;" autofocus><br><br>
<input type="submit" value=" Войти "
    style="border-radius: 100px;
    background-color:forestgreen;
    margin-top: 100px;
    font-size: 20px;
    font-weight: bold;"/>
    </form>
    <div style="display: none;">
    </div>
</body>
</html>