<?php
define('APP_START', true);
require_once '../security.php';
security_headers();
csrf_token();

include "../db_connection.php";

// Определение роли из GET параметра
$role = isset($_GET['role']) ? $_GET['role'] : 'car';

// Если роль admin - перенаправляем на admin.php
if ($role === 'admin') {
    header("Location: admin.php");
    exit;
}

// Настройки для каждой роли
$roles = [
    'car' => ['title' => 'Водитель', 'prof_value' => 'Водитель'],
    'color' => ['title' => 'Маляр', 'prof_value' => 'Маляр'],
    'diz' => ['title' => 'Дизайнер', 'prof_value' => 'Дизайнер'],
    'slesar' => ['title' => 'Слесарь', 'prof_value' => 'Слесарь'],
    'svar' => ['title' => 'Сварщик', 'prof_value' => 'Сварщик']
];

// Проверка существования роли
if (!isset($roles[$role])) {
    $role = 'car';
}

$current_role = $roles[$role];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="admin2.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<title><?php echo $current_role['title']; ?></title>
<style>
.role-info {
    background: #4CAF50;
    color: white;
    padding: 10px;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
    border-radius: 5px;
}
</style>
</head>

<body>

<div class="role-info">
    Вы вошли как: <?php echo $current_role['title']; ?>
</div>

<div class="tableFixHead">

<?php
// Получаем последний код доступа
$userid = '';
$sql = "SELECT cod FROM cod ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $userid = $row['cod'];
}

// Вывод таблицы отчётов
if ($userid) {
    $stmt = $conn->prepare("SELECT id, date, tz, otchet, class_work, prof, name, cod FROM otchet WHERE cod = ? ORDER BY id DESC");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        echo "<table style='border:1px solid black; border-collapse:collapse; position:sticky; max-height:400px;'>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Дата</th>
                    <th>Тех.задание</th>
                    <th>Отчёт</th>
                </tr>
            </thead>
            <tbody>";
        while ($row = $res->fetch_assoc()) {
            $class_work = $row['class_work'];
            $prof = $row['prof'];
            $name = $row['name'];
            $cod = $row['cod'];
            echo "<tr data-id='{$row['id']}' data-class_work='" . htmlspecialchars($class_work) . "' data-prof='" . htmlspecialchars($prof) . "' data-name='" . htmlspecialchars($name) . "' data-cod='" . htmlspecialchars($cod) . "'
                       data-tz='" . htmlspecialchars($row['tz']) . "' data-otchet='" . htmlspecialchars($row['otchet']) . "'>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tz']) . "</td>";
            echo "<td>" . htmlspecialchars($row['otchet']) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Нет отчётов.</p>";
    }
    $stmt->close();
} else {
    echo "<p>Код доступа не найден.</p>";
}

// Обработка действий (Добавить, Изменить, Удалить)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['Save']) || isset($_POST['Change']) || isset($_POST['Delete']))) {
    csrf_check();

    date_default_timezone_set('Europe/Moscow');
    $date = date('Y-m-d H:i:s');

    if (isset($_POST['Save'])) {
        $class_work = $_POST['class_work'] ?? '';
        $prof       = $_POST['prof'] ?? $current_role['prof_value'];
        $name       = $_POST['name'] ?? '';
        $tz         = $_POST['tz'] ?? '';
        $otchet     = $_POST['otchet'] ?? '';
        $cod        = $_POST['cod'] ?? '';
        $stmt = $conn->prepare("INSERT INTO otchet (class_work, prof, name, tz, otchet, date, cod) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $class_work, $prof, $name, $tz, $otchet, $date, $cod);
        if ($stmt->execute()) {
            echo "<script>window.location.href = 'worker.php?role=" . $role . "';</script>";
        } else {
            echo "Ошибка добавления: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_POST['Change'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $class_work = $_POST['class_work'] ?? '';
            $prof       = $_POST['prof'] ?? '';
            $name       = $_POST['name'] ?? '';
            $tz         = $_POST['tz'] ?? '';
            $otchet     = $_POST['otchet'] ?? '';
            $cod        = $_POST['cod'] ?? '';
            $stmt = $conn->prepare("UPDATE otchet SET class_work=?, prof=?, name=?, tz=?, otchet=?, cod=? WHERE id=?");
            $stmt->bind_param("ssssssi", $class_work, $prof, $name, $tz, $otchet, $cod, $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'worker.php?role=" . $role . "';</script>";
            } else {
                echo "Ошибка изменения: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "<script>alert('Выберите отчёт для изменения'); window.location.href='worker.php?role=" . $role . "';</script>";
        }
    }

    if (isset($_POST['Delete'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM otchet WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'worker.php?role=" . $role . "';</script>";
            } else {
                echo "Ошибка удаления: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "<script>alert('Выберите отчёт для удаления'); window.location.href='worker.php?role=" . $role . "';</script>";
        }
    }
    $conn->close();
    exit;
}
?>
</div>

<p>Вставьте тех.задание из таблицы и напишите отчёт о проделанной работе</p>

<div class="div2">
    <form method="POST">
        <?php echo csrf_token_field(); ?>
        <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
        <input type="hidden" id="id" name="id" value="0">
        <input type="hidden" id="cod" name="cod" readonly value="<?php echo htmlspecialchars($userid ?? ''); ?>">
        <input type="hidden" id="class_work" name="class_work" value="<?php echo htmlspecialchars($class_work ?? ''); ?>">
        <input type="text" id="prof" name="prof" value="<?php echo htmlspecialchars($prof ?? $current_role['prof_value']); ?>">
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>">
        <textarea id="tz" name="tz" cols="40" rows="4" placeholder="Тех.задание"></textarea>
        <textarea id="otchet" name="otchet" cols="40" rows="4" placeholder="Отчёт"></textarea>
        <input type="submit" name="Save" value=" Добавить ">
        <input type="submit" name="Change" value=" Изменить ">
        <input type="submit" name="Delete" value=" Удалить ">
    </form>
</div>

<h2>Заказы</h2>
<div class="tableFixHead">
<?php
$sql = "SELECT * FROM zakaz ORDER BY Id DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    echo "<table style='border:1px solid black; border-collapse:collapse; position:sticky; max-height:400px;'>
        <thead style='border:1px solid black'>
            <tr>
                <th>№</th>
                <th>Дата</th>
                <th>Изделие</th>
                <th>Стоимость</th>
                <th>Оплата</th>
                <th>Процесс выполнения</th>
            </tr>
        </thead>";
    while ($row = $result->fetch_assoc()) {
        $id = htmlspecialchars($row['Id']);
        echo "<tbody>
            <tr class='clickable-row' data-id='$id'>
                <td>" . htmlspecialchars($row['Id']) . "</td>
                <td>" . htmlspecialchars($row['date']) . "</td>
                <td>" . htmlspecialchars($row['izdelie']) . "</td>
                <td>" . htmlspecialchars($row['Prise']) . "</td>
                <td>" . htmlspecialchars($row['Pay']) . "</td>
                <td>" . htmlspecialchars($row['Proces']) . "</td>
            </tr>
        </tbody>";
    }
    echo "</table>";
} else {
    echo "<p>Нет заказов.</p>";
}
?>
</div>

<br>
<div class="form_row">
    <a href="mater.php" target="_blank" rel="noopener noreferrer" class="admin-btn">Материалы</a>
    <a href="rashod.php" target="_blank" rel="noopener noreferrer" class="admin-btn">Прочие расходы</a>
    <a href="working_process.php" target="_blank" rel="noopener noreferrer" class="admin-btn">Ход рабочего процесса</a>
    
    <form method="post" enctype="multipart/form-data" style="display: inline-block; margin-left: 10px;">
        <?php echo csrf_token_field(); ?>
        <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">
        <input type="file" name="file">
        <input type="submit" value="Загрузить файл!">
    </form>
</div>
<br>

<?php
if (isset($_FILES['file'])) {
    include_once('zakaz_img.php');
    $check = can_upload($_FILES['file']);
    if ($check === true) {
        make_upload($_FILES['file']);
        echo "<strong>Файл успешно загружен!</strong>";
    } else {
        echo "<strong>$check</strong>";
    }
}
$conn->close();
?>

<script>
$(function() {
    $('#otchet, #tz').on('click', function(e) {
        e.stopPropagation();
    });
    
    $('tr').click(function() {
        $('#id').val($(this).data('id'));
        $('#class_work').val($(this).data('class_work'));
        $('#prof').val($(this).data('prof'));
        $('#name').val($(this).data('name'));
        $('#cod').val($(this).data('cod'));
        $('#tz').val($(this).data('tz'));
        $('#otchet').val($(this).data('otchet'));
    });
});

// Клик по строке таблицы заказов – переход на страницу редактирования
$(function() {
    $('.clickable-row').click(function() {
        var id = $(this).data('id');
        window.location.href = 'zakazDetailSpec.php?id=' + id;
    });
});
</script>

</body>
</html>