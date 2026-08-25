<?php
session_start();

// ✅ Проверка авторизации
if (!isset($_SESSION['user_role'])) {
    header('Location: /vhod.php');
    exit;
}

// ✅ Определяем ссылку для всех ролей
if ($_SESSION['user_role'] === 'admin') {
    $zp = 'admin.php';
} elseif (in_array($_SESSION['user_role'], ['car', 'color', 'diz', 'slesar', 'svar'])) {
    $zp = 'worker.php';
} else {
    header('Location: /vhod.php');
    exit;
}

include "../db_connection.php";

// ===== ФУНКЦИЯ ПРОВЕРКИ ЛИМИТА СБРОСОВ =====
function checkResetLimit($logFile, $ip, $maxResets = 30, $timeWindow = 864) { // 86400 = 24 часа
    $now = time();
    $log = [];
    
    if (file_exists($logFile)) {
        $log = json_decode(file_get_contents($logFile), true) ?: [];
    }
    
    // Удаляем записи старше 1 часа
    $log = array_filter($log, function($item) use ($now, $timeWindow) {
        return ($now - $item['time']) < $timeWindow;
    });
    
    // Считаем сбросы с этого IP за последний час
    $resets = array_filter($log, function($item) use ($ip) {
        return $item['ip'] === $ip;
    });
    
    if (count($resets) >= $maxResets) {
        // Вычисляем, когда можно будет сбросить снова
        $times = array_column($resets, 'time');
        sort($times);
        $oldestReset = $times[0]; // самый старый сброс из лимита
        $availableTime = $oldestReset + $timeWindow;
        $remaining = $availableTime - $now;
        $minutes = ceil($remaining / 36); //3600 В СУТКИ
        
        return [
            'allowed' => false, 
            'message' => "⏳ Лимит сбросов ({$maxResets}) исчерпан. Повторите через {$minutes} минут."
        ];
    }
    
    return ['allowed' => true];
}

// ===== ФУНКЦИЯ ЛОГИРОВАНИЯ СБРОСА =====
function logReset($logFile, $ip) {
    $log = [];
    if (file_exists($logFile)) {
        $log = json_decode(file_get_contents($logFile), true) ?: [];
    }
    $log[] = ['ip' => $ip, 'time' => time()];
    file_put_contents($logFile, json_encode($log));
}

// ===== ФУНКЦИЯ ПРОВЕРКИ - НУЖЕН ЛИ СБРОС =====
function isResetNeeded($conn, $checkFile) {
    // Создаем хеш текущего состояния БД (только структура + кол-во записей)
    $hash = '';
    $tables = ['dostup', 'otchet', 'zakaz', 'workes']; // основные таблицы
    
    foreach ($tables as $table) {
        $result = $conn->query("SELECT COUNT(*) as cnt FROM `$table`");
        if ($result) {
            $row = $result->fetch_assoc();
            $hash .= $table . ':' . $row['cnt'] . ';';
        }
    }
    $currentHash = md5($hash);
    
    // Если файл с хешем существует и хеши совпадают
    if (file_exists($checkFile)) {
        $savedHash = file_get_contents($checkFile);
        if ($savedHash === $currentHash) {
            return ['needed' => false, 'message' => '✅ Данные уже соответствуют исходному состоянию. Сброс не требуется.'];
        }
    }
    
    return ['needed' => true, 'hash' => $currentHash];
}

// ===== ОБРАБОТКА ПОСТ-ЗАПРОСА =====
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    // --- 1. Проверка лимита ---
    $logFile = __DIR__ . '/reset_log.json';
    $ip = $_SERVER['REMOTE_ADDR'];
    $check = checkResetLimit($logFile, $ip);
    
    if (!$check['allowed']) {
        $message = $check['message'];
        $messageType = 'danger';
    } else {
        // --- 2. Проверка - нужен ли сброс ---
        $checkFile = __DIR__ . '/reset_checksum.txt';
        $resetCheck = isResetNeeded($conn, $checkFile);
        
        if (!$resetCheck['needed']) {
            $message = $resetCheck['message'];
            $messageType = 'success';
        } else {
            // --- 3. Выполняем сброс ---
            // 3.1 Получаем список таблиц
            $tables = [];
            $result = $conn->query("SHOW TABLES");
            while ($row = $result->fetch_array()) {
                $tables[] = $row[0];
            }
            
            // 3.2 Удаляем все таблицы
            $conn->query("SET FOREIGN_KEY_CHECKS = 0");
            foreach ($tables as $table) {
                $conn->query("DROP TABLE IF EXISTS `$table`");
            }
            $conn->query("SET FOREIGN_KEY_CHECKS = 1");
            
            // 3.3 Импортируем дамп
            $dumpPath = __DIR__ . '/dump.sql';
            if (!file_exists($dumpPath)) {
                $message = "❌ Ошибка: файл dump.sql не найден!";
                $messageType = 'danger';
            } else {
                $sql = file_get_contents($dumpPath);
                if ($conn->multi_query($sql)) {
                    do {
                        while ($conn->more_results()) {
                            $conn->next_result();
                        }
                    } while ($conn->next_result());
                    
                    // --- 4. Логируем успешный сброс ---
                    logReset($logFile, $ip);
                    
                    // --- 5. Сохраняем хеш нового состояния ---
                    file_put_contents($checkFile, $resetCheck['hash']);
                    
                    $message = "✅ Данные успешно сброшены!";
                    $messageType = 'success';
                } else {
                    $message = "❌ Ошибка импорта: " . $conn->error;
                    $messageType = 'danger';
                }
            }
        }
    }
}

// ===== СЧИТАЕМ ЗАПИСИ ДЛЯ ОТОБРАЖЕНИЯ =====
$counts = [];
$result = $conn->query("SHOW TABLES");
if ($result) {
    while ($row = $result->fetch_array()) {
        $table = $row[0];
        $countResult = $conn->query("SELECT COUNT(*) as cnt FROM `$table`");
        if ($countResult) {
            $count = $countResult->fetch_assoc();
            $counts[$table] = $count['cnt'];
        }
    }
}

// ===== ПОКАЗЫВАЕМ ОСТАВШЕЕСЯ КОЛИЧЕСТВО СБРОСОВ =====
$logFile = __DIR__ . '/reset_log.json';
$ip = $_SERVER['REMOTE_ADDR'];
$now = time();
$log = [];
if (file_exists($logFile)) {
    $log = json_decode(file_get_contents($logFile), true) ?: [];
}
$log = array_filter($log, function($item) use ($now) {
    return ($now - $item['time']) < 86400; // последние 24 часа
});
$resets = array_filter($log, function($item) use ($ip) {
    return $item['ip'] === $ip;
});
$remainingResets = 30 - count($resets);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Сброс демо-данных</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .btn { padding: 12px 24px; font-size: 16px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        .btn-danger:disabled { background: #6c757d; cursor: not-allowed; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .danger { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background: #f2f2f2; }
        .info { background: #e7f3ff; padding: 10px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <a href="<?php echo htmlspecialchars($zp); ?>">← На главную</a>
    <h1>🔄 Сброс демо-данных</h1>
    
    <?php if (!empty($message)): ?>
        <div class="<?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <div class="info">
        <strong>ℹ️ Информация:</strong><br>
        • Доступно сбросов: <strong><?php echo $remainingResets; ?></strong> из 30 в сутки<br>
        • Сброс восстанавливает все данные к исходному состоянию<br>
        • Все внесенные изменения будут потеряны
    </div>
    
    <form method="POST">
        <button type="submit" name="reset" class="btn btn-danger" 
                onclick="return confirm('Внимание! Все изменения будут потеряны. Продолжить?')"
                <?php echo $remainingResets <= 0 ? 'disabled' : ''; ?>>
            🔄 Сбросить демо-данные <?php echo $remainingResets > 0 ? "({$remainingResets} осталось)" : "(лимит исчерпан)"; ?>
        </button>
    </form>
    
    <h2>📊 Текущее состояние БД</h2>
    <table class="table">
        <tr><th>Таблица</th><th>Записей</th></tr>
        <?php foreach ($counts as $table => $count): ?>
        <tr><td><?php echo htmlspecialchars($table); ?></td><td><?php echo $count; ?></td></tr>
        <?php endforeach; ?>
    </table>
</body>
</html>