<?php
define('APP_START', true);
require_once '../security.php';
security_headers();
require_once 'auth_check.php';
include "../db_connection.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="admin2.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<title>Заказы</title>
<style>
    .clickable-row {
        cursor: pointer;
    }
</style>
</head>
<body>
<br>
<h2>Заказы</h2>
<div class="tableFixHead">
<?php
$sql = "SELECT * FROM zakaz ORDER BY Id DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    echo "<table style='border:1px solid black; border-collapse:collapse; position:sticky; height:400px;'>
        <thead style='border:1px solid black'>
        <tr>
            <th>№</th><th>Дата</th><th>Изделие</th><th>Стоимость</th><th>Оплата</th><th>Процесс выполнения</th>
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
$conn->close();
?>
</div>
<br>
<div>
    <a href="zakazDetail.php?new=1" class="admin-btn">Добавить заказ</a>
</div>
<br>
<script>
// Прокрутка таблицы вниз
$('div').animate({scrollTop:5000},'50');

// Клик по строке таблицы – переход на страницу редактирования
$(function() {
    $('.clickable-row').click(function() {
        var id = $(this).data('id');
        window.location.href = 'zakazDetail.php?id=' + id;
    });
});
</script>
</body>
</html>