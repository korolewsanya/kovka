<?php
define('APP_START', true);
require_once '../security.php';
require_once 'auth_check.php';
security_headers();

// Обработка POST-запроса (если есть)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    
    if(isset($_POST["rashod"])){
        $rashod = strip_tags(trim($_POST["rashod"]));
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin2.css" />
    <title>Прочие расходы</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
</head>
<body>
    <div>
        <h2>Прочие расходы</h2>
    </div>
    <div class="tableFixHead">
        <?php include "rashodTab.php"; ?>
    </div>
    <div class="div2">
        <form method="POST">
            <?php echo csrf_token_field(); ?>
            <input type="text" id="id" name="id" readonly size="5" placeholder="№"><br>
            <input type="text" id="date" name="date" required size="15" placeholder="Дата и время"><br>
            <input type="text" id="name" name="name" required size="20" placeholder="Наименование"><br>
            <input type="text" id="kup" name="kup" required size="10" placeholder="Куплено"><br>
            <input type="text" id="izras" name="izras" required size="10" placeholder="Израсходовано"><br>
            <input type="text" id="ost" name="ost" required size="7" placeholder="Остаток"><br>
            <input type="text" id="prise" name="prise" required size="15" placeholder="Стоимость единицы"><br>
            <input type="number" id="itogo" name="itogo" required size="14" placeholder="Итоговая стоимость"><br>
            <input type="submit" id="save" name="Save" value=" Добавить ">
            <input type="submit" id="change" name="Change" value=" Изменить ">
            <input type="submit" id="delete" name="Delete" value=" Удалить ">
        </form>
    </div>
    
    <script>
        $(function() {
            $('tr').click(function() {
                $('#id').val($(this).find("td:first").text());
                $('#date').val($(this).find('td:eq(1)').text());
                $('#name').val($(this).find('td:eq(2)').text());
                $('#kup').val($(this).find('td:eq(3)').text());
                $('#izras').val($(this).find('td:eq(4)').text());
                $('#ost').val($(this).find('td:eq(5)').text());
                $('#prise').val($(this).find('td:eq(6)').text());
                $('#itogo').val($(this).find('td:eq(7)').text());
            });
            
            // Устанавливаем текущую дату, если поле пустое
            if (!$('#date').val()) {
                $('#date').val(moment().format('YYYY-MM-DD HH:mm:ss'));
            }
        });
        
        $('div').animate({scrollTop:5000},'50');
    </script>
</body>
</html>