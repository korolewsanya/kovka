<?php
require_once '../security.php';
include "../db_connection.php";
security_headers();

// Получаем поисковый запрос
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Если запрос пустой - перенаправляем на главную
if (empty($query)) {
    header('Location: glav.php');
    exit;
}

// Массив соответствия категорий и таблиц
$categories = [
    'Мангалы' => 'mangal',
    'Лавочки' => 'lavo4ki',
    'Навесы' => 'kozirek',
    'Оградки' => 'ogradki',
    'Заборы' => 'zabor',
    'Ворота' => 'vorota',
    'Мебель' => 'mebel',
    'Решетки' => 'reshetki',
    'Мелочи' => 'melo4i'
];

// Определяем категорию по запросу
$found_category = null;
$found_table = null;
$found_title = null;

foreach ($categories as $title => $table) {
    if (stripos($title, $query) !== false || stripos($query, $title) !== false) {
        $found_category = $table;
        $found_title = $title;
        $found_table = $table;
        break;
    }
}

// Если категория найдена - перенаправляем на страницу с товарами
if ($found_category) {
    header('Location: izdelie.php?category=' . urlencode($found_category));
    exit;
}

// Если точное совпадение не найдено, ищем по всем таблицам
// Собираем все товары со всех таблиц
$all_products = [];
$table_names = [
    'mangal' => 'Мангалы',
    'lavo4ki' => 'Лавочки',
    'kozirek' => 'Навесы',
    'ogradki' => 'Оградки',
    'zabor' => 'Заборы',
    'vorota' => 'Ворота',
    'mebel' => 'Мебель',
    'reshetki' => 'Решетки',
    'melo4i' => 'Мелочи'
];

foreach ($table_names as $table => $title) {
    $sql = "SELECT id, izdelie, image, Prise, '$table' as category, '$title' as category_title FROM `" . $conn->real_escape_string($table) . "` WHERE izdelie LIKE '%" . $conn->real_escape_string($query) . "%'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $all_products[] = $row;
        }
    }
}

// Если нашли товары - показываем результаты
if (!empty($all_products)) {
    $page_title = 'Результаты поиска: ' . htmlspecialchars($query);
} else {
    $page_title = 'Ничего не найдено';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <link rel="stylesheet" type="text/css" href="izdelie.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Shade&family=Marck+Script&family=Lobster&display=swap" rel="stylesheet">
    <style>
        .search-header {
            text-align: center;
            padding: 20px;
            background: white;
            margin-bottom: 20px;
            border-radius: 10px;
        }
        .search-header h1 {
            margin: 0;
            color: #8B4513;
            font-family: 'Lobster', cursive;
        }
        .search-header p {
            margin: 10px 0 0 0;
            color: #666;
        }
        .no-results {
            text-align: center;
            padding: 40px;
            font-size: 1.2rem;
            color: #666;
        }
        .no-results a {
            color: #8B4513;
            text-decoration: underline;
        }
        .back-link {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 30px;
            background: #8B4513;
            color: white;
            border-radius: 20px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .back-link:hover {
            background: #A0522D;
        }
        .category-badge {
            display: inline-block;
            background: #8B4513;
            color: white;
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 0.7rem;
            margin-top: 5px;
        }
        .product-wrapper {
            display: inline-block;
        }
        @media only screen and (max-width: 480px) {
            .search-header h1 {
                font-size: 1.5rem;
            }
            .product-wrapper {
                display: block;
                width: 100%;
            }
            .product-wrapper .container4 {
                width: 95vw;
                margin: 10px auto;
            }
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

    <div class="search-header">
        <h1>Результаты поиска</h1>
        <p>Запрос: "<?= htmlspecialchars($query) ?>"</p>
        <?php if (!empty($all_products)): ?>
            <p>Найдено товаров: <?= count($all_products) ?></p>
        <?php endif; ?>
        <a href="glav.php" class="back-link">← Вернуться на главную</a>
    </div>

    <main>
        <?php if (!empty($all_products)): ?>
            <?php foreach ($all_products as $row): 
                $imageFile = trim($row['image']);
                $imageFile = basename($imageFile);
                $imagePath = "../img/{$imageFile}";
            ?>
                <div class="product-wrapper">
                    <div class="container4">
                        <a href="zakaz.php?category=<?= urlencode($row['category']) ?>&id=<?= (int)$row['id'] ?>">
                            <div class="product-image">
                                <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($row['izdelie']) ?>">
                            </div>
                            <p class="pris"><?= htmlspecialchars($row['izdelie']) ?></p>
                            <p class="pris"><?= number_format((float)$row['Prise'], 0, '.', ' ') ?> руб.</p>
                            <span class="category-badge"><?= htmlspecialchars($row['category_title']) ?></span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results">
                <p>😕 По вашему запросу "<strong><?= htmlspecialchars($query) ?></strong>" ничего не найдено.</p>
                <p>Попробуйте изменить запрос или вернуться на <a href="glav.php">главную страницу</a>.</p>
                <p>Возможно, вас заинтересуют наши категории:</p>
                <p style="margin-top: 15px;">
                    <a href="izdelie.php?category=mangal" style="margin: 5px;">Мангалы</a>
                    <a href="izdelie.php?category=lavo4ki" style="margin: 5px;">Лавочки</a>
                    <a href="izdelie.php?category=kozirek" style="margin: 5px;">Навесы</a>
                    <a href="izdelie.php?category=ogradki" style="margin: 5px;">Оградки</a>
                    <a href="izdelie.php?category=zabor" style="margin: 5px;">Заборы</a>
                    <a href="izdelie.php?category=vorota" style="margin: 5px;">Ворота</a>
                    <a href="izdelie.php?category=mebel" style="margin: 5px;">Мебель</a>
                    <a href="izdelie.php?category=reshetki" style="margin: 5px;">Решетки</a>
                    <a href="izdelie.php?category=melo4i" style="margin: 5px;">Мелочи</a>
                </p>
            </div>
        <?php endif; ?>
    </main>

    <?php include "footer.html"; ?>
</body>
</html>