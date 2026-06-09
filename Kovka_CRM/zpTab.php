<?php
if (!function_exists('csrf_check')) {
    require_once '../security.php';
    require_once 'auth_check.php';
}
include "../db_connection.php";

// ---- ВЫВОД ТАБЛИЦЫ (с экранированием) ----
$sql = "SELECT * FROM zp";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    echo "<table style='border:1px solid black; border-collapse:collapse; position:sticky; max-height:400px;'>
        <thead style='border:1px solid black'>
            <tr>
                <th>№</th><th>Дата</th><th>Должность</th>
                <th>Ф.И.О.</th><th>Начислено</th><th>Получено</th>
            </tr>
        </thead>";
    while ($row = $result->fetch_assoc()) {
        echo "<tbody>";
        echo "<tr>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["id"]) . "</td>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["date"]) . "</td>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["spec"]) . "</td>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["nachis"]) . "</td>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["poluch"]) . "</td>";
        echo "</tr></tbody>";
    }
    echo "</table>";
} else {
    echo "<p>Нет данных в таблице zp.</p>";
}

// ---- ОБРАБОТКА POST ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    if (isset($_POST['Save'])) {
        $date   = $_POST['date'] ?? '';
        $spec   = $_POST['spec'] ?? '';
        $name   = $_POST['name'] ?? '';
        $nachis = $_POST['nachis'] ?? '';
        $poluch = $_POST['poluch'] ?? '';
        
        $stmt = $conn->prepare("INSERT INTO zp (date, spec, name, nachis, poluch) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $date, $spec, $name, $nachis, $poluch);
        if ($stmt->execute()) {
            echo "<script>window.location.href = 'zp.php';</script>";
        } else {
            echo "Ошибка добавления: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_POST['Change'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $date   = $_POST['date'] ?? '';
            $spec   = $_POST['spec'] ?? '';
            $name   = $_POST['name'] ?? '';
            $nachis = $_POST['nachis'] ?? '';
            $poluch = $_POST['poluch'] ?? '';
            
            $stmt = $conn->prepare("UPDATE zp SET date=?, spec=?, name=?, nachis=?, poluch=? WHERE id=?");
            $stmt->bind_param("sssssi", $date, $spec, $name, $nachis, $poluch, $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'zp.php';</script>";
            } else {
                echo "Ошибка обновления: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    if (isset($_POST['Delete'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM zp WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'zp.php';</script>";
            } else {
                echo "Ошибка удаления: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
$conn->close();
?>