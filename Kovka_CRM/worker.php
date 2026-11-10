<?php
define('APP_START', true);
require_once '../security.php';
require_once 'auth_check.php';
security_headers();

include "../db_connection.php";

// Ссылка на выход
echo '<a href="logout.php" style="float: right; margin: 10px;">Выйти</a>';

// ===== Берем роль И код из СЕССИИ =====
$role = $_SESSION['user_role'] ?? 'car';
$user_cod = $_SESSION['user_cod'] ?? '';

// Белый список допустимых ролей
$allowed_roles = ['admin', 'car', 'color', 'diz', 'slesar', 'svar'];
if (!in_array($role, $allowed_roles)) {
    $role = 'car';
}

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

$current_role = $roles[$role];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="admin2.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<title><?php echo htmlspecialchars($current_role['title']); ?></title>
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
    Вы вошли как: <?php echo htmlspecialchars($current_role['title']); ?>
</div>

<br>
<br>

<div class="tableFixHead">

<?php
// ===== ИСПОЛЬЗУЕМ КОД ИЗ СЕССИИ =====
if ($user_cod) {
    // Ищем пользователя по коду, чтобы получить его class_work (уникальный ID)
    $found_user = null;
    $r = mysqli_query($conn, "SELECT `id`, `class_work`, `prof`, `name`, `cod` FROM `dostup`");
    if ($r) {
        while ($user = mysqli_fetch_assoc($r)) {
            if (password_verify($user_cod, $user['cod'])) {
                $found_user = $user;
                break;
            }
        }
    }

    if ($found_user) {
        $user_class_work = $found_user['class_work'];
        $user_prof = $found_user['prof'];
        $user_name = $found_user['name'];

        // Вывод таблицы отчётов по class_work (уникальный ID пользователя)
        $stmt = $conn->prepare("SELECT id, date, tz, otchet, class_work, prof, name, cod FROM otchet WHERE class_work = ? ORDER BY id DESC");
        $stmt->bind_param("s", $user_class_work);
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
                echo "<tr data-id='{$row['id']}' data-class_work='" . htmlspecialchars($row['class_work']) . "' data-prof='" . htmlspecialchars($row['prof']) . "' data-name='" . htmlspecialchars($row['name']) . "' data-cod='" . htmlspecialchars($row['cod']) . "'
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
        echo "<p>Пользователь не найден.</p>";
    }
} else {
    echo "<p>Код доступа не найден в сессии.</p>";
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
        
        $tz = strip_tags($tz);
        $otchet = strip_tags($otchet);
        $name = strip_tags($name);
        
        $stmt = $conn->prepare("INSERT INTO otchet (class_work, prof, name, tz, otchet, date, cod) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $class_work, $prof, $name, $tz, $otchet, $date, $cod);
        if ($stmt->execute()) {
            echo "<script>window.location.href = 'worker.php';</script>";
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
            
            $tz = strip_tags($tz);
            $otchet = strip_tags($otchet);
            $name = strip_tags($name);
            
            $stmt = $conn->prepare("UPDATE otchet SET class_work=?, prof=?, name=?, tz=?, otchet=?, cod=? WHERE id=?");
            $stmt->bind_param("ssssssi", $class_work, $prof, $name, $tz, $otchet, $cod, $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'worker.php';</script>";
            } else {
                echo "Ошибка изменения: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "<script>alert('Выберите отчёт для изменения'); window.location.href='worker.php';</script>";
        }
    }

    if (isset($_POST['Delete'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM otchet WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'worker.php';</script>";
            } else {
                echo "Ошибка удаления: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "<script>alert('Выберите отчёт для удаления'); window.location.href='worker.php';</script>";
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
        <input type="hidden" id="id" name="id" value="0">
        <input type="hidden" id="cod" name="cod" readonly value="<?php echo htmlspecialchars($user_cod ?? ''); ?>">
        <input type="hidden" id="class_work" name="class_work" value="">
        <input type="text" id="prof" name="prof" readonly value="<?php echo htmlspecialchars($current_role['prof_value']); ?>"> <br>
        <input type="text" id="name" name="name" value="" placeholder="Имя"> <br>
        <textarea id="tz" name="tz" cols="40" rows="4" placeholder="Тех.задание"></textarea> <br>
        <textarea id="otchet" name="otchet" cols="40" rows="4" placeholder="Отчёт"></textarea> <br>
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
        $id = htmlspecialchars($row['Id'] ?? '');
        $date = htmlspecialchars($row['date'] ?? '');
        $izdelie = htmlspecialchars($row['izdelie'] ?? '');
        $prise = htmlspecialchars($row['Prise'] ?? '');
        $pay = htmlspecialchars($row['Pay'] ?? '');
        $proces = htmlspecialchars($row['Proces'] ?? '');
        
        echo "<tbody>
            <tr class='clickable-row' data-id='$id'>
                <td>$id</td>
                <td>$date</td>
                <td>$izdelie</td>
                <td>$prise</td>
                <td>$pay</td>
                <td>$proces</td>
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
        echo "<strong>" . htmlspecialchars($check) . "</strong>";
    }
}
$conn->close();
?>

<script>
    // Прокрутка таблицы вниз
$('div').animate({scrollTop:1000},'100');

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

$(function() {
    $('.clickable-row').click(function() {
        var id = $(this).data('id');
        window.location.href = 'zakazDetailSpec.php?id=' + id;
    });
});
</script>

</body>
</html>