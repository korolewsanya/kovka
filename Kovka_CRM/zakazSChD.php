<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.0 405 Method Not Allowed');
    die('Method not allowed');
}
csrf_check();

include "../db_connection.php";

$response = '';

if (isset($_POST['Save'])) {
    $date    = $_POST['date'] ?? '';
    $izdelie = $_POST['izdelie'] ?? '';
    $image   = $_POST['image'] ?? '';
    $dlina   = $_POST['dlina'] ?? '';
    $shirina = $_POST['shirina'] ?? '';
    $visota  = $_POST['visota'] ?? '';
    $prise   = (float)($_POST['prise'] ?? 0);
    $pay     = (float)($_POST['pay'] ?? 0);
    $proces  = $_POST['proces'] ?? '';
    $name    = $_POST['name'] ?? '';
    $tel     = $_POST['tel'] ?? '';
    $email   = $_POST['email'] ?? '';
    $coment  = $_POST['coment'] ?? '';
    
    // Защита от XSS в текстовых полях
    $izdelie = strip_tags($izdelie);
    $proces = strip_tags($proces);
    $name = strip_tags($name);
    $coment = strip_tags($coment);
    
    // Защита имени файла от path traversal
    $image = basename($image);

    $stmt = $conn->prepare("INSERT INTO zakaz (date, izdelie, image, Dlina, Shirina, Visota, Prise, Pay, Proces, Name, Tel, Email, Coment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssddsssss", $date, $izdelie, $image, $dlina, $shirina, $visota, $prise, $pay, $proces, $name, $tel, $email, $coment);
    if ($stmt->execute()) {
        $response = "<script>alert('Заказ добавлен!'); window.location.href = 'zakaz.php';</script>";
    } else {
        $response = "Ошибка добавления: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_POST['Change'])) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $date    = $_POST['date'] ?? '';
        $izdelie = $_POST['izdelie'] ?? '';
        $image   = $_POST['image'] ?? '';
        $dlina   = $_POST['dlina'] ?? '';
        $shirina = $_POST['shirina'] ?? '';
        $visota  = $_POST['visota'] ?? '';
        $prise   = (float)($_POST['prise'] ?? 0);
        $pay     = (float)($_POST['pay'] ?? 0);
        $proces  = $_POST['proces'] ?? '';
        $name    = $_POST['name'] ?? '';
        $tel     = $_POST['tel'] ?? '';
        $email   = $_POST['email'] ?? '';
        $coment  = $_POST['coment'] ?? '';
        
        // Защита от XSS в текстовых полях
        $izdelie = strip_tags($izdelie);
        $proces = strip_tags($proces);
        $name = strip_tags($name);
        $coment = strip_tags($coment);
        
        // Защита имени файла от path traversal
        $image = basename($image);

        $stmt = $conn->prepare("UPDATE zakaz SET date=?, izdelie=?, image=?, Dlina=?, Shirina=?, Visota=?, Prise=?, Pay=?, Proces=?, Name=?, Tel=?, Email=?, Coment=? WHERE Id=?");
        $stmt->bind_param("ssssssddsssssi", $date, $izdelie, $image, $dlina, $shirina, $visota, $prise, $pay, $proces, $name, $tel, $email, $coment, $id);
        if ($stmt->execute()) {
            $response = "<script>alert('Заказ изменён!'); window.location.href = 'zakaz.php';</script>";
        } else {
            $response = "Ошибка изменения: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response = "Неверный ID";
    }
}

if (isset($_POST['Delete'])) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM zakaz WHERE Id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $response = "<script>alert('Заказ удалён!'); window.location.href = 'zakaz.php';</script>";
        } else {
            $response = "Ошибка удаления: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response = "Неверный ID";
    }
}

$conn->close();
echo $response;
?>