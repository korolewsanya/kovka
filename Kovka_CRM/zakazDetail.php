<?php
define('APP_START', true);
require_once '../security.php';
require_once 'auth_check.php';
security_headers();

// Получаем id заказа (0 – новый заказ)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isNew = ($id == 0);
$izdelie = $image = $dlina = $shirina = $visota = $prise = $pay = $proces = $name = $tel = $email = $coment = $date = '';
$idi = '';

if (!$isNew) {
    include "../db_connection.php";
    $stmt = $conn->prepare("SELECT * FROM zakaz WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $idi = $row['Id'];
        $date = $row['date'];
        $izdelie = $row['izdelie'];
        $image = $row['image'];
        $dlina = $row['Dlina'];
        $shirina = $row['Shirina'];
        $visota = $row['Visota'];
        $prise = $row['Prise'];
        $pay = $row['Pay'];
        $proces = $row['Proces'];
        $name = $row['Name'];
        $tel = $row['Tel'];
        $email = $row['Email'];
        $coment = $row['Coment'];
    }
    $stmt->close();
    $conn->close();
} else {
    date_default_timezone_set('Europe/Moscow');
    $date = date('Y-m-d H:i:s');
}

// Определение пути к изображению по названию изделия
$imageUrl = '';
if (!empty($image)) {
    // Очищаем имя файла от опасных символов
    $safe_image = basename($image);
    $safe_image = preg_replace('/[^a-zA-Zа-яА-ЯёЁ0-9._-]/u', '', $safe_image);
    
    $folders = [
        'мангал' => 'Мангалы', 'лавочка' => 'Лавочки', 'козырек' => 'Навесы',
        'забор' => 'Заборы', 'ворота' => 'Ворота', 'оградк' => 'Оградки',
        'решётк' => 'Решетки', 'мебель' => 'Мебель', 'кровать' => 'Мебель',
        'стол' => 'Мебель', 'стул' => 'Мебель', 'подсвечник' => 'Мелочи',
        'подставка' => 'Мелочи', 'урна' => 'Мелочи'
    ];
    $found = false;
    $search = mb_strtolower($izdelie);
    foreach ($folders as $keyword => $folder) {
        if (strpos($search, $keyword) !== false) {
            $imageUrl = "../img/" . $safe_image;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $imageUrl = "../img/placeholder.png";
    }
} else {
    $imageUrl = "../img/placeholder.png";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="admin2.css" />
<title>Заказ</title>
<style>
    .form-container {
        display: flex;
        justify-content: space-between;
    }
    .order-image {
        width: 45%;
        text-align: center;
    }
    .order-image img {
        max-width: 100%;
        max-height: 300px;
        object-fit: contain;
        border: 1px solid #ddd;
        border-radius: 8px;
    }
    .order-form {
        width: 50%;
    }
</style>
</head>
<body>
<div class="form-container">
    <div class="order-image">
        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="Изображение товара">
    </div>
    <div class="order-form">
        <form method="POST" action="zakazSChD.php">
            <?php echo csrf_token_field(); ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($idi); ?>">
            <p>Номер заказа: <input type="text" name="id_display" readonly value="<?php echo htmlspecialchars($idi); ?>"></p>
            <p>Дата: <input type="text" name="date" value="<?php echo htmlspecialchars($date); ?>"></p>
            <p>Изделие: <input type="text" name="izdelie" value="<?php echo htmlspecialchars($izdelie); ?>"></p>
            <p>Имя файла изображения: <input type="text" name="image" value="<?php echo htmlspecialchars($image); ?>"></p>
            <p>Длина: <input type="text" name="dlina" value="<?php echo htmlspecialchars($dlina); ?>"></p>
            <p>Ширина: <input type="text" name="shirina" value="<?php echo htmlspecialchars($shirina); ?>"></p>
            <p>Высота: <input type="text" name="visota" value="<?php echo htmlspecialchars($visota); ?>"></p>
            <p>Стоимость: <input type="number" step="0.01" name="prise" value="<?php echo htmlspecialchars($prise); ?>"></p>
            <p>Оплата: <input type="number" step="0.01" name="pay" value="<?php echo htmlspecialchars($pay); ?>"></p>
            <p>Процесс выполнения: <textarea name="proces"><?php echo htmlspecialchars($proces); ?></textarea></p>
            <p>Имя: <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>"></p>
            <p>Телефон: <input type="text" name="tel" value="<?php echo htmlspecialchars($tel); ?>"></p>
            <p>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>"></p>
            <p>Комментарий: <textarea name="coment"><?php echo htmlspecialchars($coment); ?></textarea></p>
            <p>
                <input type="submit" name="Save" value="Добавить" <?php if (!$isNew) echo 'style="display:none"'; ?>>
                <input type="submit" name="Change" value="Изменить" <?php if ($isNew) echo 'style="display:none"'; ?>>
                <input type="submit" name="Delete" value="Удалить" <?php if ($isNew) echo 'style="display:none"'; ?>>
            </p>
        </form>
    </div>
</div>
</body>
</html>