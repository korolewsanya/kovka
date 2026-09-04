<?php
define('APP_START', true);
require_once '../security.php';
require_once 'auth_check.php';
security_headers();

//$uploadDir = 'C:\\xampp\\htdocs\\Загрузк изображений на сервер\\Upload_Image_to_Server_in_Db\\uploads\\';
$webPath = '../img/';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    if (isset($_POST['rename'])) {
        $old = basename($_POST['oldname']);
        $new = basename($_POST['newname']);
        $new = str_replace(' ', '_', $new);
        if (!empty($new)) {
            $oldPath = $webPath . $old;
            $newPath = $webPath . $new;
            if (file_exists($oldPath) && rename($oldPath, $newPath)) {
                $message = "Переименовано: $old → $new";
            } else {
                $message = "Ошибка переименования.";
            }
        } else {
            $message = "Новое имя не может быть пустым.";
        }
    }
}

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
$files = [];
if (is_dir($webPath)) {
    $allFiles = scandir($webPath);
    foreach ($allFiles as $item) {
        if ($item === '.' || $item === '..') continue;
        $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
        if (in_array($ext, $allowedExtensions)) {
            $files[] = $item;
        }
    }
    usort($files, function($a, $b) use ($webPath) {
        return filemtime($webPath . $b) - filemtime($webPath . $a);
    });
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление изображениями</title>
    <style>
        body { font-family: system-ui; background: #f0f2f5; margin: 20px; }
        h1 { margin-bottom: 20px; }
        .gallery { display: flex; flex-wrap: wrap; gap: 20px; }
        .card { background: white; border-radius: 12px; padding: 10px; width: 180px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; }
        .card img { width: 100%; height: 140px; object-fit: cover; border-radius: 8px; background: #eee; }
        .filename { font-size: 0.8rem; word-break: break-all; margin: 10px 0; font-weight: bold; }
        .actions { display: flex; justify-content: center; gap: 10px; margin-bottom: 8px; }
        .delete-link { color: #c0392b; text-decoration: none; font-size: 0.75rem; cursor: pointer; }
        .rename-link { color: #1e3c72; text-decoration: none; font-size: 0.75rem; cursor: pointer; }
        .rename-form { margin-top: 8px; display: none; }
        .rename-form input { width: calc(100% - 10px); padding: 4px; font-size: 0.7rem; margin-bottom: 5px; }
        .rename-form button { background: #1e3c72; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; }
        .message { background: #d4edda; color: #155724; padding: 10px; border-radius: 8px; margin-bottom: 20px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #1e3c72; text-decoration: none; }
        hr { margin: 20px 0; }
    </style>
    <script>
        function toggleRename(id) {
            var form = document.getElementById('rename-form-' + id);
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
        function deleteFile(filename) {
            if (confirm('Удалить файл?')) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = 'delete_img.php';
                var csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = 'csrf_token';
                csrf.value = '<?php echo csrf_token(); ?>';
                var fileInput = document.createElement('input');
                fileInput.type = 'hidden';
                fileInput.name = 'file';
                fileInput.value = filename;
                form.appendChild(csrf);
                form.appendChild(fileInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</head>
<body>
<a href="admin.php" class="back-link">&larr; На главную</a>
<h1>Галерея изображений</h1>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="gallery">
    <?php if (count($files) === 0): ?>
        <p>Нет изображений в папке <img src="" alt="" srcset="">.</p>
    <?php else: ?>
        <?php foreach ($files as $index => $filename): ?>
            <?php $imageUrl = $webPath . urlencode($filename); ?>
            <div class="card">
                <img src="<?= $imageUrl ?>" alt="<?= htmlspecialchars($filename) ?>">
                <div class="filename"><?= htmlspecialchars($filename) ?></div>
                <div class="actions">
                    <a href="#" onclick="deleteFile('<?= addslashes($filename) ?>'); return false;" class="delete-link">Удалить</a>
                    <a href="#" class="rename-link" onclick="toggleRename(<?= $index ?>); return false;">Переименовать</a>
                </div>
                <div id="rename-form-<?= $index ?>" class="rename-form">
                    <form method="POST">
                        <?php echo csrf_token_field(); ?>
                        <input type="hidden" name="oldname" value="<?= htmlspecialchars($filename) ?>">
                        <input type="text" name="newname" value="<?= htmlspecialchars($filename) ?>">
                        <button type="submit" name="rename">Сохранить</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<hr>
<p>Для загрузки новых файлов используйте страницу <a href="admin.php" target="_blank">admin.php</a>.</p>
</body>
</html>