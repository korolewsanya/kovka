<?php
require_once '../security.php';
security_headers();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кованые изделия | Художественная ковка металла</title>
    
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    
    <link rel="stylesheet" type="text/css" href="glav.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    

</head>
<body>
    <header>
        <img src="img/Набор кованных элементов.png" alt="Кованые элементы">
        <img src="img/Надпись.png" alt="Кованые изделия">
        
        <!-- Поиск с datalist -->
        <div class="header-search">
            <form action="search.php" method="GET" style="display:flex; gap:8px; justify-content: center; margin: 0 auto; flex-wrap:wrap;" onsubmit="return validateForm()">
                <input type="text" id="searchInput" name="q" placeholder="Поиск..." list="productsList" autocomplete="off">
                <datalist id="productsList">
                    <option value="Мангалы">
                    <option value="Лавочки">
                    <option value="Навесы">
                    <option value="Оградки">
                    <option value="Заборы">
                    <option value="Ворота">
                    <option value="Мебель">
                    <option value="Решетки">
                    <option value="Мелочи">
                </datalist>
                <button type="submit">Найти</button>
                <p id="errorMessage" class="error-message">Поле не должно быть пустым</p>
            </form>
        </div>
        
        <img src="img/Кованные изделия.png" alt="Логотип">
    </header>
    
    <main>
        <a href="izdelie.php?category=mangal"><div style="background: url('img/Мангал_обработано.png') no-repeat center; background-size: cover;" aria-label="Мангалы"></div></a>
        <a href="izdelie.php?category=lavo4ki"><div style="background: url('img/Лавочки.jpg') no-repeat center; background-size: cover;" aria-label="Лавочки"></div></a>
        <a href="izdelie.php?category=kozirek"><div style="background: url('img/Козырек.png') no-repeat center; background-size: cover;" aria-label="Козырьки"></div></a>
        <a href="izdelie.php?category=ogradki"><div style="background: url('img/Оградки.png') no-repeat center; background-size: cover;" aria-label="Оградки"></div></a>
        <a href="izdelie.php?category=zabor"><div style="background: url('img/Забор1.jpg') no-repeat center; background-size: cover;" aria-label="Заборы"></div></a>
        <a href="izdelie.php?category=vorota"><div style="background: url('img/Ворота.png') no-repeat center; background-size: cover;" aria-label="Ворота"></div></a>
        <a href="izdelie.php?category=mebel"><div style="background: url('img/Кованная мебель_обработано.png') no-repeat center; background-size: cover;" aria-label="Мебель"></div></a>
        <a href="izdelie.php?category=reshetki"><div style="background: url('img/Решетки на окна_обработано.png') no-repeat center; background-size: cover;" aria-label="Решетки"></div></a>
        <a href="izdelie.php?category=melo4i"><div style="background: url('img/Полезные мелочи.png') no-repeat center; background-size: cover;" aria-label="Мелочи"></div></a>
    </main>
    
    <?php include "footer.html"; ?>
    
    <script>
    function validateForm() {
        var input = document.getElementById('searchInput');
        var error = document.getElementById('errorMessage');
        
        if (input.value.trim() === '') {
            error.classList.add('show');
            input.style.borderColor = 'red';
            return false;
        }
        
        error.classList.remove('show');
        input.style.borderColor = '#8B4513';
        return true;
    }
    
    // Скрываем ошибку при вводе
    document.getElementById('searchInput').addEventListener('input', function() {
        var error = document.getElementById('errorMessage');
        if (this.value.trim() !== '') {
            error.classList.remove('show');
            this.style.borderColor = '#8B4513';
        }
    });
    </script>
</body>
</html>