<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

// Подключение к БД (перенёс в начало, как и должно быть)
include "../db_connection.php";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="admin2.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<title>Админ</title>
<style>
    .admin-btn {
        display: inline-block;
        background: #1e3c72;
        color: white;
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 4px;
        margin: 5px;
        font-size: 14px;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }
    .admin-btn:hover {
        background: #0f2b4f;
    }

    /* Стили модального окна для просмотра изображений */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.8);
    }
    .modal-content {
        margin: auto;
        display: block;
        max-width: 80%;
        max-height: 80%;
    }
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }
    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }
</style>
</head>

<body>
<br>
<div class="form_row">
    <a href="zakaz.php" target="_blank" rel="noopener noreferrer" class="admin-btn">Заказы</a>
    <a href="mater.php" target="_blank" rel="noopener noreferrer" class="admin-btn">Материалы</a>
    <a href="zp.php" class="admin-btn">Зарплата</a>
    <a href="rashod.php" target="_blank" rel="noopener noreferrer" class="admin-btn">Прочие расходы</a>
    <a href="fin.php" class="admin-btn">Финансовая отчётность</a>
    <a href="workes.php" class="admin-btn">Сотрудники</a>
    <a href="dostup.php" class="admin-btn">Управление доступом</a>
    <a href="izdelie.php" class="admin-btn">Изделия</a>
    <a href="img_list.php" class="admin-btn">Изображения</a>

    <!-- Форма загрузки файла -->
    <form method="post" enctype="multipart/form-data" style="display: inline-block; margin-left: 10px;">
        <?php echo csrf_token_field(); ?>
        <input type="file" name="file">
        <input type="submit" value="Загрузить файл!">
    </form>
</div>
<br>
<h2>Заказы</h2>
<div class="tableFixHead">
<?php
$sql = "SELECT * FROM cod";
if($result = $conn->query($sql)){
    while($row = $result->fetch_array()){
        $userid = $row["cod"];
    }
}

$sql = "SELECT * FROM zakaz";
if($result = $conn->query($sql)) {
    echo "<table style='border:1px solid black; border-collapse:collapse; position:sticky; height:400px;'>
        <thead style='border:1px solid black'>
            <tr style='border:1px solid black'>
                <th style='border:1px solid black; padding-left:5px'>№</th>
                <th style='border:1px solid black; padding-left:5px'>Дата</th>
                <th style='border:1px solid black; padding-left:5px'>Изделие</th>
                <th style='border:1px solid black; padding-left:5px'>Стоимость</th>
                <th style='border:1px solid black; padding-left:5px'>Оплата</th>
                <th style='border:1px solid black; padding-left:5px'>Процесс выполнения</th>
                <th style='border:1px solid black; padding-left:5px'>Процесс выполнения</th>
             </tr>
        </thead>";
    foreach($result as $row){
        echo "<tbody style='border:1px solid black; border-collapse:collapse'>";
        echo "<tr style='border:1px solid black'>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["Id"]) . "</td>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["date"]) . "</td>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["izdelie"]) . "</td>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["Prise"]) . "</td>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["Pay"]) . "</td>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["Proces"]) . "</td>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["Proces"]) . "</td>";
        echo "</tr>";
        echo "</tbody>";
    }
    echo "</table>";
}
?>
</div>

<h2>Ход рабочего процесса</h2>
<div class="tableFixHead">
<?php
$sql = "SELECT * FROM otchet";
if($result = $conn->query($sql)) {
    echo "<table class='otchet-table' style='border:1px solid black; border-collapse:collapse; position:sticky; max-height:400px;'>
        <thead style='border:1px solid black'>
            <tr style='border:1px solid black'>
                <th style='border:1px solid black; padding-left:5px'>№</th>
                <th style='border:1px solid black; padding-left:5px'>Должность</th>
                <th style='border:1px solid black; padding-left:5px'>Имя</th>
                <th style='border:1px solid black; padding-left:5px'>Тех.задание</th>
                <th style='border:1px solid black; padding-left:5px'>Отчёт</th>
                <th style='border:1px solid black; padding-left:5px'>Код</th>
                <th style='border:1px solid black; padding-left:5px'>Классификация</th>
             </tr>
        </thead>";
    foreach($result as $row){
        $image = isset($row['image']) ? $row['image'] : '';
        $imageUrl = '';
        if (!empty($image)) {
            $imageUrl = '/Kovka_new/img/' . $image;
        }
        echo "<tbody style='border:1px solid black; border-collapse:collapse'>";
        echo "<tr style='border:1px solid black' data-image-url='" . htmlspecialchars($imageUrl) . "'>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["id"]) . "</td>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["prof"]) . "</td>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["tz"]) . "</td>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["otchet"]) . "</td>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["cod"]) . "</td>";
        echo "<td style='border:1px solid black; padding-left:5px'>" . htmlspecialchars($row["class_work"]) . "</td>";
        echo "</tr>";
        echo "</tbody>";
    }
    echo "</table>";
}
?>
</div>

<p>Для отправки тех.задания выберите специалиста</p>

<!-- Новая форма с вертикальным расположением -->
<div style="margin-top: 10px;">
    <form method="POST" style="display: block;">
        <?php echo csrf_token_field(); ?>
        
        <?php
        $specialists = [];
        $sql = "SELECT id, prof, name, cod, class_work FROM dostup ORDER BY prof, name";
        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $specialists[] = $row;
            }
        }
        ?>
        <div style="margin-bottom: 15px;">
            <label for="specialist_select">Выберите специалиста:</label>
            <select id="specialist_select" style="padding: 4px; border-radius: 4px;">
                <option value="">-- Выберите --</option>
                <?php foreach ($specialists as $spec): ?>
                    <option value="<?= htmlspecialchars($spec['id']) ?>"
                            data-cod="<?= htmlspecialchars($spec['cod']) ?>"
                            data-class_work="<?= htmlspecialchars($spec['class_work']) ?>"
                            data-prof="<?= htmlspecialchars($spec['prof']) ?>"
                            data-name="<?= htmlspecialchars($spec['name']) ?>">
                        <?= htmlspecialchars($spec['prof'] . ' - ' . $spec['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <input type="hidden" id="report_id" name="report_id" value="0">
        <div style="margin-bottom: 10px;">
            <input type="hidden" id="cod" name="cod" readonly style="color:black;" size="7" placeholder="Код доступа">
            <input type="hidden" id="class_work" name="class_work" readonly style="color:black;" size="11" placeholder="Классификация">
        </div>
        <div style="margin-bottom: 10px;">
            <input type="text" id="prof" name="prof" readonly style="color:black; width: 300px;" placeholder="Должность">
        </div>
        <div style="margin-bottom: 10px;">
            <input type="text" id="name" name="name" readonly style="color:black; width: 300px;" size="40" placeholder="Имя">
        </div>
        <div style="margin-bottom: 10px;">
            <textarea id="tz" name="tz" cols="40" rows="4" style="color:black; width: 300px;" placeholder="Тех.задание"></textarea>
        </div>
        <div>
            <button type="submit" name="save" id="save">Добавить</button>
            <button type="submit" name="edit" id="edit">Изменить</button>
            <button type="submit" name="delete" id="delete">Удалить</button>
        </div>
    </form>
</div>

<div id="div3">
<?php 
date_default_timezone_set('Europe/Moscow');
$current_date = date('Y-m-d H:i:s');

// Обработка добавления - ТОЛЬКО БЕЗОПАСНОСТЬ (подготовленный запрос)
if (isset($_POST["save"]) && isset($_POST["cod"]) && isset($_POST["class_work"]) && 
    isset($_POST["prof"]) && isset($_POST["name"]) && isset($_POST["tz"])) {
    csrf_check();

    $cod = $_POST["cod"];
    $class_work = $_POST["class_work"];
    $prof = $_POST["prof"];
    $name = $_POST["name"];
    $tz = $_POST["tz"];

    $stmt = $conn->prepare("INSERT INTO otchet (cod, class_work, prof, name, tz, date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $cod, $class_work, $prof, $name, $tz, $current_date);
    if($stmt->execute()){
        echo "<script>window.location.href = 'admin.php';</script>";
    } else{
        echo "Ошибка добавления: " . $stmt->error;
    }
    $stmt->close();
}

// Обработка изменения - ТОЛЬКО БЕЗОПАСНОСТЬ (подготовленный запрос)
if (isset($_POST["edit"]) && isset($_POST["report_id"]) && $_POST["report_id"] > 0) {
    csrf_check();
    $id = (int)$_POST["report_id"];
    $tz = $_POST["tz"];
    
    $stmt = $conn->prepare("UPDATE otchet SET tz=? WHERE id=?");
    $stmt->bind_param("si", $tz, $id);
    if($stmt->execute()){
        echo "<script>window.location.href = 'admin.php';</script>";
    } else{
        echo "Ошибка изменения: " . $stmt->error;
    }
    $stmt->close();
}

// Обработка удаления - ТОЛЬКО БЕЗОПАСНОСТЬ (подготовленный запрос)
if (isset($_POST["delete"]) && isset($_POST["report_id"]) && $_POST["report_id"] > 0) {
    csrf_check();
    $id = (int)$_POST["report_id"];
    
    $stmt = $conn->prepare("DELETE FROM otchet WHERE id=?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        echo "<script>window.location.href = 'admin.php';</script>";
    } else{
        echo "Ошибка удаления: " . $stmt->error;
    }
    $stmt->close();
}

// Обработка загрузки файла
include_once('zakaz_img.php');
if (isset($_FILES['file'])) {
    csrf_check();
    $check = can_upload($_FILES['file']);
    if ($check === true) {
        make_upload($_FILES['file']);
        echo "<script>alert('Файл успешно загружен!');</script>";
    } else {
        echo "<script>alert('$check');</script>";
    }
}
?>
</div>
<br>

<!-- Модальное окно для просмотра изображения -->
<div id="imageModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="modalImage" src="" alt="Изображение отчёта">
</div>

<script>
// Прокрутка таблицы вниз
$('div').animate({scrollTop:5000},'50');

// Вставка в поля ввода из таблицы (клик по строке) – работает для всех таблиц
$(function() {
    $('tr').click(function() {
        var report_id = $(this).find("td:eq(0)").text();   // № отчёта
        var prof = $(this).find('td:eq(1)').text();
        var name = $(this).find('td:eq(2)').text();
        var tz = $(this).find('td:eq(3)').text();
        var cod = $(this).find("td:eq(5)").text();
        var class_work = $(this).find("td:eq(6)").text();

        $('#report_id').val(report_id);
        $('#cod').val(cod);
        $('#class_work').val(class_work);
        $('#prof').val(prof);
        $('#name').val(name);
        $('#tz').val(tz);
    });
});

// Выпадающий список заполняет поля формы и сбрасывает report_id
$(function() {
    $('#specialist_select').change(function() {
        var $option = $(this).find('option:selected');
        if ($option.val() !== "") {
            $('#cod').val($option.data('cod'));
            $('#class_work').val($option.data('class_work'));
            $('#prof').val($option.data('prof'));
            $('#name').val($option.data('name'));
            $('#report_id').val('0');   // сброс ID, чтобы не путать с отчётом
        } else {
            $('#cod').val('');
            $('#class_work').val('');
            $('#prof').val('');
            $('#name').val('');
            $('#report_id').val('0');
        }
    });
});

// Скрываем 6 и 7 столбцы (код и классификация)
$('td:nth-child(6),th:nth-child(6)').hide();
$('td:nth-child(7),th:nth-child(7)').hide();

// ===== Новый функционал: просмотр изображения по клику на строку таблицы otchet =====
$(function() {
    // Клик по строкам таблицы otchet (tbody tr)
    $('table.otchet-table tbody tr').click(function(e) {
        var imageUrl = $(this).data('image-url');
        if (imageUrl) {
            $('#modalImage').attr('src', imageUrl);
            $('#imageModal').show();
        }
    });

    // Закрытие модального окна по крестику
    $('#imageModal .close').click(function() {
        $('#imageModal').hide();
    });

    // Закрытие по клику на затемнённый фон (не на изображение)
    $(window).click(function(event) {
        if ($(event.target).is('#imageModal')) {
            $('#imageModal').hide();
        }
    });
});
</script>
</body>
</html>