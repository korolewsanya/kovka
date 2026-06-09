<?php
if (!function_exists('csrf_check')) {
    require_once '../security.php';
    require_once 'auth_check.php';
}
include "../db_connection.php";

// ---- ВЫВОД ТАБЛИЦЫ (с экранированием) ----
$sql = "SELECT * FROM mater";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    echo "<table style='border:1px solid black; border-collapse:collapse; position:sticky; max-height:400px;'>
        <thead style='border:1px solid black'>
            <tr>
                <th>№</th><th>Дата</th><th>Наименование</th><th>Куплено</th>
                <th>Израсходовано</th><th>Остаток</th><th>Стоимость единицы</th><th>Итоговая стоимость</th>
            </tr>
        </thead>";
    while ($row = $result->fetch_assoc()) {
        echo "<tbody>";
        echo "<tr>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["id"]) . "</td>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["date"]) . "</td>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["kup"]) . "</td>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["izras"]) . "</td>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["ost"]) . "</td>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["prise"]) . "</td>";
        echo "<td style='padding-left:5px'>" . htmlspecialchars($row["itogo"]) . "</td>";
        echo "</td></tbody>";
    }
    echo "</table>";
} else {
    echo "<p>Нет данных в таблице mater.</p>";
}

// ---- ОБРАБОТКА POST (добавление, изменение, удаление) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    if (isset($_POST['Save'])) {
        $date  = $_POST['date'] ?? '';
        $name  = $_POST['name'] ?? '';
        $kup   = $_POST['kup'] ?? '';
        $izras = $_POST['izras'] ?? '';
        $ost   = $_POST['ost'] ?? '';
        $prise = $_POST['prise'] ?? '';
        $itogo = $_POST['itogo'] ?? 0;
        
        $stmt = $conn->prepare("INSERT INTO mater (date, name, kup, izras, ost, prise, itogo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssd", $date, $name, $kup, $izras, $ost, $prise, $itogo);
        if ($stmt->execute()) {
            echo "<script>window.location.href = 'mater.php';</script>";
        } else {
            echo "Ошибка добавления: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_POST['Change'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $date  = $_POST['date'] ?? '';
            $name  = $_POST['name'] ?? '';
            $kup   = $_POST['kup'] ?? '';
            $izras = $_POST['izras'] ?? '';
            $ost   = $_POST['ost'] ?? '';
            $prise = $_POST['prise'] ?? '';
            $itogo = $_POST['itogo'] ?? 0;
            
            $stmt = $conn->prepare("UPDATE mater SET date=?, name=?, kup=?, izras=?, ost=?, prise=?, itogo=? WHERE id=?");
            $stmt->bind_param("ssssssdi", $date, $name, $kup, $izras, $ost, $prise, $itogo, $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'mater.php';</script>";
            } else {
                echo "Ошибка обновления: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    if (isset($_POST['Delete'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM mater WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'mater.php';</script>";
            } else {
                echo "Ошибка удаления: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
$conn->close();
?>