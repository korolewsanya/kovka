<?php
require_once '../security.php';
security_headers();

// Подключение к БД
include '../db_connection.php';

$categories = [
    'mangal'   => ['table' => 'mangal',   'folder' => 'Мангалы'],
    'lavo4ki'  => ['table' => 'lavo4ki',  'folder' => 'Лавочки'],
    'kozirek'  => ['table' => 'kozirek',  'folder' => 'Навесы'],
    'ogradki'  => ['table' => 'ogradki',  'folder' => 'Оградки'],
    'zabor'    => ['table' => 'zabor',    'folder' => 'Заборы'],
    'vorota'   => ['table' => 'vorota',   'folder' => 'Ворота'],
    'mebel'    => ['table' => 'mebel',    'folder' => 'Мебель'],
    'reshetki' => ['table' => 'reshetki', 'folder' => 'Решетки'],
    'melo4i'   => ['table' => 'melo4i',   'folder' => 'Мелочи']
];

$category = $_GET['category'] ?? '';
$id = (int)($_GET['id'] ?? 0);
if (!isset($categories[$category]) || $id <= 0) {
    die('Некорректные параметры');
}

$cat = $categories[$category];
$table = $cat['table'];
$folder = $cat['folder'];

$stmt = $conn->prepare("SELECT izdelie, image, Dlina, Shirina, Visota, Prise FROM `$table` WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die('Товар не найден');
}
$row = $result->fetch_assoc();

$izdelie = $row['izdelie'];
$image = $row['image'];          // только имя файла
$dlina = $row['Dlina'];
$shirina = $row['Shirina'];
$visota = $row['Visota'];
$prise = $row['Prise'];

$imageSrc = "../img/" . $image;
date_default_timezone_set('Europe/Moscow');
$data = date("Y-m-d H:i:s");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="zakaz.css">
    <title>Заказ | <?= htmlspecialchars($izdelie) ?></title>

</head>
<body>
<div class="container">
    <div class="order-grid">
        <!-- Левая колонка: информация о товаре -->
        <div class="product-info">
            <div class="product-image">
                <img src="<?= htmlspecialchars($imageSrc) ?>" alt="<?= htmlspecialchars($izdelie) ?>">
            </div>
            <div class="product-title"><?= htmlspecialchars($izdelie) ?></div>
            <div class="specs">
                <div class="spec-item">
                    <span class="spec-label">Длина</span>
                    <span class="spec-value"><?= htmlspecialchars($dlina) ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Ширина</span>
                    <span class="spec-value"><?= htmlspecialchars($shirina) ?></span>
                </div>
                <div class="spec-item">
                    <span class="spec-label">Высота</span>
                    <span class="spec-value"><?= htmlspecialchars($visota) ?></span>
                </div>
            </div>
            <div class="price"><?= number_format($prise, 0, '.', ' ') ?> ₽</div>
        </div>

        <!-- Правая колонка: форма заказа -->
        <div class="order-form">
            <h2 style="margin-bottom: 25px; color: #2c3e50;">Оформить заказ</h2>
            <form action="finish.php" method="POST">
                <?php echo csrf_token_field(); ?>
                <div class="form-group">
                    <label>Ваше имя *</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Телефон *</label>
                    <input type="tel" name="tel" placeholder="+7 (904) 508-17-52" pattern="\+?[0-9\s\-\(\)]+" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>
                <div class="form-group">
                    <label>Комментарий к заказу</label>
                    <textarea name="coment" rows="4"></textarea>
                </div>
                <input type="hidden" name="data" value="<?= htmlspecialchars($data) ?>">
                <input type="hidden" name="izdelie" value="<?= htmlspecialchars($izdelie) ?>">
                <input type="hidden" name="image" value="<?= htmlspecialchars($image) ?>">
                <input type="hidden" name="dlina" value="<?= htmlspecialchars($dlina) ?>">
                <input type="hidden" name="shirina" value="<?= htmlspecialchars($shirina) ?>">
                <input type="hidden" name="visota" value="<?= htmlspecialchars($visota) ?>">
                <input type="hidden" name="prise" value="<?= htmlspecialchars($prise) ?>">
                <button type="submit">Отправить заказ</button>
                <div style="text-align: center;">
                    <a href="javascript:history.back()" class="back-link">← Вернуться к выбору</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
<?php include "footer.html"; ?>
</html>