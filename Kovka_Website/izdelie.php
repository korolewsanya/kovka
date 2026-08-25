<?php
require_once '../security.php';
include "../db_connection.php";
security_headers();

// Получаем категорию из адресной строки (например, ?category=mangal)
$category = $_GET['category'] ?? '';

// Получаем параметры фильтрации
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 0;
$sort = $_GET['sort'] ?? 'default';

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

// Формируем WHERE условие для фильтра по цене
$where = '';
if ($min_price > 0 && $max_price > 0) {
    $where = " WHERE Prise BETWEEN $min_price AND $max_price";
} elseif ($min_price > 0) {
    $where = " WHERE Prise >= $min_price";
} elseif ($max_price > 0) {
    $where = " WHERE Prise <= $max_price";
}

// Формируем ORDER BY для сортировки
$order = '';
if ($sort === 'price_asc') {
    $order = " ORDER BY Prise ASC";
} elseif ($sort === 'price_desc') {
    $order = " ORDER BY Prise DESC";
} elseif ($sort === 'name_asc') {
    $order = " ORDER BY izdelie ASC";
} elseif ($sort === 'name_desc') {
    $order = " ORDER BY izdelie DESC";
}

// ВАЖНО: экранируем имя таблицы для защиты от SQL-инъекций
$sql = "SELECT id, izdelie, image, Prise FROM `" . $conn->real_escape_string($table) . "`" . $where . $order;
// Выполняем запрос к базе данных и сохраняем результат
$result = $conn->query($sql);

// Получаем минимальную и максимальную цену для слайдера
$price_sql = "SELECT MIN(Prise) as min_price, MAX(Prise) as max_price FROM `" . $conn->real_escape_string($table) . "`";
$price_result = $conn->query($price_sql);
$price_row = $price_result->fetch_assoc();
$global_min = (int)$price_row['min_price'];
$global_max = (int)$price_row['max_price'];

// Если фильтр не задан, используем глобальные значения
if ($min_price == 0) $min_price = $global_min;
if ($max_price == 0) $max_price = $global_max;
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
        
        /* Стили для фильтра */
        .filter-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 0 15px 20px 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            justify-content: center;
        }
        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .filter-group label {
            font-weight: 500;
            color: #333;
        }
        .filter-group input[type="number"] {
            width: 100px;
            padding: 8px 12px;
            border: 2px solid #8B4513;
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
        }
        .filter-group input[type="number"]:focus {
            border-color: #A0522D;
        }
        .filter-group select {
            padding: 8px 12px;
            border: 2px solid #8B4513;
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
            background: white;
            cursor: pointer;
        }
        .filter-group select:focus {
            border-color: #A0522D;
        }
        .filter-btn {
            padding: 8px 25px;
            background: #8B4513;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.3s;
        }
        .filter-btn:hover {
            background: #A0522D;
        }
        .filter-reset {
            padding: 8px 20px;
            background: #e8e0d8;
            color: #5a3d2b;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background 0.3s;
            text-decoration: none;
        }
        .filter-reset:hover {
            background: #d4c8bc;
        }
        .filter-info {
            font-size: 0.9rem;
            color: #666;
            text-align: center;
            width: 100%;
            margin-top: 5px;
        }
        
        @media only screen and (max-width: 480px) {
            .filter-container {
                flex-direction: column;
                padding: 15px;
            }
            .filter-group {
                width: 100%;
                justify-content: center;
            }
            .filter-group input[type="number"] {
                width: 80px;
            }
            .filter-btn, .filter-reset {
                width: 100%;
                text-align: center;
            }
        }
        @media (min-width: 481px) and (max-width:768px) {
            .filter-container {
                padding: 15px;
            }
            .filter-group input[type="number"] {
                width: 80px;
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

<!-- Фильтр по цене -->
<article class="filter-container">
    <form method="GET" action="izdelie.php" style="display:flex; flex-wrap:wrap; gap:15px; align-items:center; justify-content:center; width:100%;">
        <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
        
        <article class="filter-group">
            <label>Цена:</label>
            <input type="number" name="min_price" placeholder="от" value="<?= $min_price > 0 ? $min_price : '' ?>" min="<?= $global_min ?>">
            <span>—</span>
            <input type="number" name="max_price" placeholder="до" value="<?= $max_price > 0 ? $max_price : '' ?>" min="<?= $global_min ?>">
        </article>
        
        <article class="filter-group">
            <label>Сортировка:</label>
            <select name="sort">
                <option value="default" <?= $sort === 'default' ? 'selected' : '' ?>>По умолчанию</option>
                <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Цена ↑ (сначала дешёвые)</option>
                <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Цена ↓ (сначала дорогие)</option>
                <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Название А-Я</option>
                <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Название Я-А</option>
            </select>
        </article>
        
        <button type="submit" class="filter-btn">Применить</button>
        <a href="izdelie.php?category=<?= htmlspecialchars($category) ?>" class="filter-reset">Сбросить</a>
    </form>
    
    <article class="filter-info">
        <?php if ($result && $result->num_rows > 0): ?>
            Найдено товаров: <?= $result->num_rows ?>. 
            Цены: от <?= number_format($global_min, 0, '.', ' ') ?> ₽ до <?= number_format($global_max, 0, '.', ' ') ?> ₽
        <?php endif; ?>
    </article>
</article>

<main>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()):
            $imageFile = trim($row['image']);
            // Если в БД хранится полный путь, извлекаем только имя файла
            $imageFile = basename($imageFile);
            // Формируем путь к изображению
            $imagePath = "../img/{$imageFile}";
            ?>
            <div class="container4">
                <a href="zakaz.php?category=<?= urlencode($category) ?>&id=<?= (int)$row['id'] ?>">
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