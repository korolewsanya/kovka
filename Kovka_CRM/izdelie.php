<?php
define('APP_START', true);
require_once '../security.php';
require_once 'auth_check.php';
security_headers();

include "../db_connection.php";

// Вместо массива используем список ключей и функцию для получения названия
$tableKeys = ['mangal', 'lavo4ki', 'kozirek', 'zabor', 'vorota', 'ogradki', 'reshetki', 'mebel', 'melo4i'];

function getTableName($key) {
    if ($key == 'mangal') {
        return 'Мангалы';
    } elseif ($key == 'lavo4ki') {
        return 'Лавочки';
    } elseif ($key == 'kozirek') {
        return 'Козырьки';
    } elseif ($key == 'zabor') {
        return 'Заборы';
    } elseif ($key == 'vorota') {
        return 'Ворота';
    } elseif ($key == 'ogradki') {
        return 'Оградки';
    } elseif ($key == 'reshetki') {
        return 'Решётки';
    } elseif ($key == 'mebel') {
        return 'Мебель';
    } elseif ($key == 'melo4i') {
        return 'Мелочи';
    } else {
        return '';
    }
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $table = trim($_POST['table'] ?? '');
    $id = (int)($_POST['id'] ?? 0);

    // Проверяем, существует ли категория, используя функцию getTableName
    if (empty($table) || getTableName($table) === '') {
        $message = "Выберите категорию изделия";
    } elseif ($action === 'add') {
        $izdelie = trim($_POST['izdelie'] ?? '');
        $image = trim($_POST['image'] ?? '');
        $dlina = trim($_POST['dlina'] ?? '');
        $shirina = trim($_POST['shirina'] ?? '');
        $visota = trim($_POST['visota'] ?? '');
        $prise = (float)($_POST['prise'] ?? 0);
        
        $stmt = $conn->prepare("INSERT INTO `$table` (izdelie, image, Dlina, Shirina, Visota, Prise) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssd", $izdelie, $image, $dlina, $shirina, $visota, $prise);
        if ($stmt->execute()) {
            $message = "Изделие добавлено";
        } else {
            $message = "Ошибка добавления: " . $stmt->error;
        }
        $stmt->close();
    } elseif ($action === 'edit' && $id > 0) {
        $izdelie = trim($_POST['izdelie'] ?? '');
        $image = trim($_POST['image'] ?? '');
        $dlina = trim($_POST['dlina'] ?? '');
        $shirina = trim($_POST['shirina'] ?? '');
        $visota = trim($_POST['visota'] ?? '');
        $prise = (float)($_POST['prise'] ?? 0);
        
        $stmt = $conn->prepare("UPDATE `$table` SET izdelie=?, image=?, Dlina=?, Shirina=?, Visota=?, Prise=? WHERE id=?");
        $stmt->bind_param("sssssdi", $izdelie, $image, $dlina, $shirina, $visota, $prise, $id);
        if ($stmt->execute()) {
            $message = "Изделие обновлено";
        } else {
            $message = "Ошибка обновления: " . $stmt->error;
        }
        $stmt->close();
    } elseif ($action === 'delete' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM `$table` WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Изделие удалено";
        } else {
            $message = "Ошибка удаления: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Неизвестное действие";
    }
    
    header("Location: izdelie.php?msg=" . urlencode($message));
    exit;
}

if (isset($_GET['msg'])) {
    $message = htmlspecialchars($_GET['msg']);
}

// Подготавливаем данные для JavaScript (аналог старого массива $tables)
$jsTables = [];
foreach ($tableKeys as $key) {
    $jsTables[$key] = getTableName($key);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление изделиями</title>
    <link rel="stylesheet" href="izdelie.css">
</head>
<body>
<div class="container">
    <a href="admin.php" class="back-link">&larr; На главную</a>
    <h1>Управление изделиями</h1>
    
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <button class="add-btn" id="showAddFormBtn">+ Добавить новое изделие</button>
    
    <div class="tabs">
        <button class="tab-btn active" data-table="all">Все изделия</button>
        <?php foreach ($tableKeys as $key): ?>
            <button class="tab-btn" data-table="<?= $key ?>"><?= htmlspecialchars(getTableName($key)) ?></button>
        <?php endforeach; ?>
    </div>
    
    <div id="itemsContainer" class="grid"></div>
    
    <div id="formPanel" class="form-panel" style="display: none;">
        <h3 id="formTitle">Добавление изделия</h3>
        <form id="itemForm" method="POST" action="izdelie.php">
            <?php echo csrf_token_field(); ?>
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="id" id="editId" value="0">
            <div class="form-group">
                <label>Тип изделия:</label>
                <select name="table" id="tableSelect" required>
                    <option value="">-- Выберите тип --</option>
                    <?php foreach ($tableKeys as $key): ?>
                        <option value="<?= $key ?>"><?= htmlspecialchars(getTableName($key)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Название (izdelie):</label>
                <input type="text" name="izdelie" id="izdelie" required>
            </div>
            <div class="form-group">
                <label>Изображение (image):</label>
                <input type="text" name="image" id="image" placeholder="имя_файла.jpg">
                <small>Только имя файла (скопироват из раздела Изображения)</small>
            </div>
            <div class="form-group">
                <label>Длина (Dlina):</label>
                <input type="text" name="dlina" id="dlina" placeholder="например 1200 мм">
            </div>
            <div class="form-group">
                <label>Ширина (Shirina):</label>
                <input type="text" name="shirina" id="shirina">
            </div>
            <div class="form-group">
                <label>Высота (Visota):</label>
                <input type="text" name="visota" id="visota">
            </div>
            <div class="form-group">
                <label>Цена (Prise):</label>
                <input type="number" step="0.01" name="prise" id="prise" required>
            </div>
            <div class="form-group">
                <button type="submit" id="submitBtn">Сохранить</button>
                <button type="button" id="cancelBtn">Отмена</button>
                <button type="button" id="deleteBtn" class="delete-btn" style="display:none;">Удалить</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Передаём данные из PHP в JavaScript
    window.tablesData = <?= json_encode($jsTables) ?>;
</script>
<script src="izdelie.js"></script>
</body>
</html>