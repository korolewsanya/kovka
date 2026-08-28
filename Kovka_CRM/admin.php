<?php
define('APP_START', true);
require_once '../security.php';
require_once 'auth_check.php';  // проверка авторизации
check_role('admin');            // проверка, что это именно admin
security_headers();

// Подключение к БД
include "../db_connection.php";

//Ссылка на выход
echo '<a href="logout.php" style="float: right; margin: 10px; color: red; font-weight: bold;">Выйти</a>';

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="admin2.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="admin.js"></script> <!-- Подключение внешнего JS файла -->
<title>Админ</title>
</head>

<body>
<br>
<div class="form_row">
    <a href="zakaz.php" target="_blank" rel="noopener noreferrer">Заказы</a>
    <a href="mater.php" target="_blank" rel="noopener noreferrer">Материалы</a>
    <a href="zp.php" >Зарплата</a>
    <a href="rashod.php" target="_blank" rel="noopener noreferrer">Расходы</a>
    <a href="fin.php">Финансы</a>
    <a href="workes.php">Сотрудники</a>
    <a href="dostup.php">Доступ</a>
    <a href="izdelie.php">Изделия</a>
    <a href="img_list.php">Изображения</a>

    <!-- Форма загрузки файла -->
    <form method="post" enctype="multipart/form-data" style="display: inline-block; margin-left: 10px;">
        <?php echo csrf_token_field(); ?>
        <input type="file" name="file">
        <input type="submit" value="Загрузить файл!">
    </form>
   </div>
<br>
<!-- <p style="color:red">___При тестировании рекомендуется сбросить данные до исходных значений___</p>
<a href="reset.php">🔄 Сбросить демо-данные</a> -->

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

<!-- форма с вертикальным расположением -->
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

// Обработка добавления
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

// Обработка изменения
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

// Обработка удаления
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

</body>
</html>