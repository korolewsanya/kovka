<?php
include "../db_connection.php";

// Получаем категорию из адресной строки (например, ?category=mangal)
$category = $_GET['category'] ?? '';

// Если категория — «мангалы», работаем с таблицей mangal // И так далее для остальных категорий...
if ($category === 'mangal') {
    $table = 'mangal';
    $folder = 'Мангалы';
    $title = 'Мангалы';
} elseif ($category === 'lavo4ki') {
    $table = 'lavo4ki';
    $folder = 'Лавочки';
    $title = 'Лавочки';
} elseif ($category === 'kozirek') {
    $table = 'kozirek';
    $folder = 'Навесы';
    $title = 'Козырьки';
} elseif ($category === 'ogradki') {
    $table = 'ogradki';
    $folder = 'Оградки';
    $title = 'Оградки';
} elseif ($category === 'zabor') {
    $table = 'zabor';
    $folder = 'Заборы';
    $title = 'Заборы';
} elseif ($category === 'vorota') {
    $table = 'vorota';
    $folder = 'Ворота';
    $title = 'Ворота';
} elseif ($category === 'mebel') {
    $table = 'mebel';
    $folder = 'Мебель';
    $title = 'Мебель';
} elseif ($category === 'reshetki') {
    $table = 'reshetki';
    $folder = 'Решетки';
    $title = 'Решётки';
} elseif ($category === 'melo4i') {
    $table = 'melo4i';
    $folder = 'Мелочи';
    $title = 'Полезные мелочи';
} else {
    // Если категория не распознана — показываем ошибку
    die('Неверная категория');
}

// Формируем запрос: берём нужные поля из нужной таблицы
$sql = "SELECT id, izdelie, image, Prise FROM `$table`";
// Выполняем запрос к базе данных и сохраняем результат
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" type="text/css" href="mangal.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Shade&family=Marck+Script&family=Lobster&display=swap" rel="stylesheet">
    <style>
        /* Дополнительные стили для карточек, чтобы изображения не обрезались */
        .container4 {
            display: inline-block;
            width: 220px;
            margin: 15px;
            text-align: center;
            vertical-align: top;
        }
        .product-image {
            width: 200px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            border-radius: 10px;
            overflow: hidden;
            margin: 0 auto;
        }
        .product-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .pris {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
<header>
    <p class="p pris2">Сделаем по индивидуальному заказу</p>
    <img class="img" src="img/Надпись.png">
    <p class="p">
        <a href="tel:+79001316418">Тел.: +79001316418</a>
        <a href="https://wa.me/79045081752"><img src="img/WhatsApp.png"></a>
        <a href="https://t.me/Aleksandr_Korolew"><img src="img/Telegram.png"></a>
    </p>
</header>
<main>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()):
            $imageFile = trim($row['image']);
            // Если в БД хранится полный путь, извлекаем только имя файла
            $imageFile = basename($imageFile);
            // Формируем путь к изображению
            $imagePath = "../img/{$imageFile}"
            ?>
            <div class="container4">
                <a href="zakaz.php?category=<?= htmlspecialchars($category) ?>&id=<?= (int)$row['id'] ?>">
                    <div class="product-image">
                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($row['izdelie']) ?>">
                    </div>
                    <p class="pris"><?= number_format((float)$row['Prise'], 0, '.', ' ') ?> руб.</p>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Нет товаров в этой категории.</p>
    <?php endif; ?>
</main>
<?php include "footer.html"; ?>
</body>
</html>
