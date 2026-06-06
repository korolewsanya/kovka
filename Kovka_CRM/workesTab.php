<?php
include "../db_connection.php";

// ---- ВЫВОД ТАБЛИЦЫ (с экранированием) ----
$sql = "SELECT * FROM workes";
if ($result = $conn->query($sql)) {
    echo "<table style='border:1px solid black; border-collapse:collapse; position:sticky; max-height:400px;'>
        <thead style='border:1px solid black'>
        <tr><th>№</th><th>Должность</th><th>Ф.И.О.</th><th>Телефон</th><th>Email</th><th>Адрес</th><th>Дата рождения</th><th>Прочее</th></tr>
        </thead>";
    while ($row = $result->fetch_assoc()) {
        echo "<tbody><tr>";
        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["spec"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["tel"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["adres"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["data"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["proch"]) . "</td>";
        echo "</tr></tbody>";
    }
    echo "</table>";
}

// ---- ОБРАБОТКА POST (один блок) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();  // проверяем токен

    if (isset($_POST['Save'])) {
        $spec  = $_POST['spec'] ?? '';
        $name  = $_POST['name'] ?? '';
        $tel   = $_POST['tel'] ?? '';
        $email = $_POST['email'] ?? '';
        $adres = $_POST['adres'] ?? '';
        $data  = $_POST['data'] ?? '';
        $proch = $_POST['proch'] ?? '';
        
        $stmt = $conn->prepare("INSERT INTO workes (spec, name, tel, email, adres, data, proch) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $spec, $name, $tel, $email, $adres, $data, $proch);
        if ($stmt->execute()) {
            echo "<script>window.location.href = 'workes.php';</script>";
        } else {
            echo "Ошибка: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_POST['Change'])) {
        $id = (int)($_POST['id'] ?? 0);
        $spec  = $_POST['spec'] ?? '';
        $name  = $_POST['name'] ?? '';
        $tel   = $_POST['tel'] ?? '';
        $email = $_POST['email'] ?? '';
        $adres = $_POST['adres'] ?? '';
        $data  = $_POST['data'] ?? '';
        $proch = $_POST['proch'] ?? '';
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE workes SET spec=?, name=?, tel=?, email=?, adres=?, data=?, proch=? WHERE id=?");
            $stmt->bind_param("sssssssi", $spec, $name, $tel, $email, $adres, $data, $proch, $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'workes.php';</script>";
            } else {
                echo "Ошибка: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    if (isset($_POST['Delete'])) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM workes WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'workes.php';</script>";
            } else {
                echo "Ошибка: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
$conn->close();
?>