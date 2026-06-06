<?php
if (!function_exists('csrf_check')) {
    require_once __DIR__ . '/../Ковка_сайт/config/security.php';
}
include "../db_connection.php";

$c = '';
$po = '';
if (isset($_POST["calendar_c"])) {
    $c = $_POST["calendar_c"];
}
if (isset($_POST["calendar_po"])) {
    $po = $_POST["calendar_po"];
}

// Если форма отправлена – проверяем CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pok'])) {
    csrf_check();
}

if ($c && $po) {
    $stmt = $conn->prepare("SELECT izdelie, date, Pay FROM zakaz WHERE date BETWEEN ? AND ?");
    $stmt->bind_param("ss", $c, $po);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<table style='border:1px solid black; border-collapse:collapse; position:sticky; max-height:400px;'>
            <thead style='border:1px solid black'>
            <tr><th>Изделие</th><th>Дата</th><th>Полученная сумма</th></tr>
            </thead>";
        while ($row = $result->fetch_assoc()) {
            echo "<tbody><tr>";
            echo "<td>" . htmlspecialchars($row["izdelie"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["Pay"]) . "</td>";
            echo "</tr></tbody>";
        }
        echo "</table>";
        $stmt->close();
    } else {
        echo "<p>Нет доходов за выбранный период.</p>";
    }

    // Сумма
    $stmt_sum = $conn->prepare("SELECT SUM(Pay) AS Pay_sum FROM zakaz WHERE date BETWEEN ? AND ?");
    $stmt_sum->bind_param("ss", $c, $po);
    $stmt_sum->execute();
    $res_sum = $stmt_sum->get_result();
    $sum_row = $res_sum->fetch_assoc();
    $sum = $sum_row['Pay_sum'] ?? 0;
    if ($sum != 0) {
        echo "<br><p style='color:green; margin:10px;'>Доходы за период с $c по $po составили: $sum</p>";
    }
    $stmt_sum->close();
} else {
    echo "<p>Выберите период и нажмите «Показать».</p>";
}
$conn->close();
?>