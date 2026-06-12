<?php
include "../db_connection.php";
require_once 'auth_check.php';

// ВЫВОД ТАБЛИЦЫ (с экранированием)
$sql = "SELECT * FROM dostup";
if ($result = $conn->query($sql)) {
    echo "<table style='border:1px solid black; border-collapse:collapse; position:sticky; max-height:400px;'>
        <thead style='border:1px solid black'>
        <tr><th>№</th><th>Классификация</th><th>Должность</th><th>Ф.И.О.</th><th>Код доступа</th></tr>
        </thead>";
    while ($row = $result->fetch_assoc()) {
        echo "<tbody><tr>";
        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["class_work"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["prof"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["cod"]) . "</td>";
        echo "</tr></tbody>";
    }
    echo "</table>";
}

// ---- ОБРАБОТКА POST (только если пришли данные из формы) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка CSRF-токена (функция определена в security.php)
    csrf_check();

    if (isset($_POST['Save'])) {
        $class_work = $_POST['class_work'] ?? '';
        $prof       = $_POST['prof'] ?? '';
        $name       = $_POST['name'] ?? '';
        $cod        = $_POST['cod'] ?? '';
        $stmt = $conn->prepare("INSERT INTO dostup (class_work, prof, name, cod) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $class_work, $prof, $name, $cod);
        if ($stmt->execute()) {
            echo "<script>window.location.href = 'dostup.php';</script>";
        } else {
            echo "Ошибка: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_POST['Change'])) {
        $id = (int)($_POST['id'] ?? 0);
        $class_work = $_POST['class_work'] ?? '';
        $prof       = $_POST['prof'] ?? '';
        $name       = $_POST['name'] ?? '';
        $cod        = $_POST['cod'] ?? '';
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE dostup SET class_work=?, prof=?, name=?, cod=? WHERE id=?");
            $stmt->bind_param("ssssi", $class_work, $prof, $name, $cod, $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'dostup.php';</script>";
            } else {
                echo "Ошибка: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    if (isset($_POST['Delete'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM dostup WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'dostup.php';</script>";
            } else {
                echo "Ошибка: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
$conn->close();
?>