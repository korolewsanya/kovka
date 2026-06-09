<?php
if (!function_exists('csrf_check')) {
    require_once '../security.php';
    require_once 'auth_check.php';
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pok'])) {
    csrf_check();
}

if ($c && $po) {
    $sql = "SELECT name, date, itogo FROM mater WHERE date BETWEEN ? AND ?
            UNION ALL
            SELECT name, date, itogo FROM rashod WHERE date BETWEEN ? AND ?
            UNION ALL
            SELECT names, date, poluch FROM zp WHERE date BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $c, $po, $c, $po, $c, $po);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<table style='border:1px solid black; border-collapse:collapse; position:sticky; max-height:400px;'>
            <thead style='border:1px solid black'>
            <tr><th>Наименование</th><th>Дата</th><th>Потраченная сумма</th></tr>
            </thead>";
        while ($row = $result->fetch_assoc()) {
            $name = $row["name"] ?? $row["names"] ?? '';
            echo "<tbody><tr>";
            echo "<td>" . htmlspecialchars($name) . "</td>";
            echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["itogo"] ?? $row["poluch"] ?? '') . "</td>";
            echo "</tr></tbody>";
        }
        echo "</table>";
        $stmt->close();
    } else {
        echo "<p>Нет расходов за выбранный период.</p>";
    }

    // Подсчёт общей суммы
    $stmt1 = $conn->prepare("SELECT SUM(itogo) AS total FROM mater WHERE date BETWEEN ? AND ?");
    $stmt1->bind_param("ss", $c, $po);
    $stmt1->execute();
    $res1 = $stmt1->get_result();
    $sum1 = (float)($res1->fetch_assoc()['total'] ?? 0);
    $stmt1->close();

    $stmt2 = $conn->prepare("SELECT SUM(itogo) AS total FROM rashod WHERE date BETWEEN ? AND ?");
    $stmt2->bind_param("ss", $c, $po);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $sum2 = (float)($res2->fetch_assoc()['total'] ?? 0);
    $stmt2->close();

    $stmt3 = $conn->prepare("SELECT SUM(poluch) AS total FROM zp WHERE date BETWEEN ? AND ?");
    $stmt3->bind_param("ss", $c, $po);
    $stmt3->execute();
    $res3 = $stmt3->get_result();
    $sum3 = (float)($res3->fetch_assoc()['total'] ?? 0);
    $stmt3->close();

    $total = $sum1 + $sum2 + $sum3;
    if ($total != 0) {
        echo "<br><p style='color:red; margin:10px;'>Расходы за период с $c по $po составили: $total</p>";
    }
} else {
    echo "<p>Выберите период и нажмите «Показать».</p>";
}
$conn->close();
?>