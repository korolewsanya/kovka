<?php
if (!function_exists('csrf_check')) {
    require_once __DIR__ . '/../Ковка_сайт/config/security.php';
}
include "../db_connection.php";

// ---- ВЫВОД ТАБЛИЦЫ ----
$sql = "SELECT * FROM fin";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    echo "<table style='border:1px solid black; border-collapse:collapse; position:sticky; max-height:400px;'>
        <thead style='border:1px solid black'>
        <tr><th>№</th><th>Дата</th><th>Доход</th><th>Расход</th><th>Прибыль</th></tr>
        </thead>";
    while ($row = $result->fetch_assoc()) {
        echo "<tbody><tr>";
        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["dohod"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["rashod"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["prib"]) . "</td>";
        echo "</tr></tbody>";
    }
    echo "</table>";
} else {
    echo "<p>Нет данных в таблице fin.</p>";
}

// ---- ОБРАБОТКА POST ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    if (isset($_POST['Save'])) {
        $date   = $_POST['date'] ?? '';
        $dohod  = (float)($_POST['dohod'] ?? 0);
        $rashod = (float)($_POST['rashod'] ?? 0);
        $prib   = (float)($_POST['prib'] ?? 0);
        
        $stmt = $conn->prepare("INSERT INTO fin (date, dohod, rashod, prib) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sddd", $date, $dohod, $rashod, $prib);
        if ($stmt->execute()) {
            echo "<script>window.location.href = 'fin.php';</script>";
        } else {
            echo "Ошибка добавления: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_POST['Change'])) {
        $id = (int)($_POST['id'] ?? 0);
        $date   = $_POST['date'] ?? '';
        $dohod  = (float)($_POST['dohod'] ?? 0);
        $rashod = (float)($_POST['rashod'] ?? 0);
        $prib   = (float)($_POST['prib'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE fin SET date=?, dohod=?, rashod=?, prib=? WHERE id=?");
            $stmt->bind_param("sdddi", $date, $dohod, $rashod, $prib, $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'fin.php';</script>";
            } else {
                echo "Ошибка обновления: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    if (isset($_POST['Delete'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM fin WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'fin.php';</script>";
            } else {
                echo "Ошибка удаления: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
$conn->close();
?>