<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

if(isset($_POST["zp"])){
    $zp = htmlentities($_POST["zp"]);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin2.css" />
    <title>Зарплата</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
</head>
<body>
   <a href="<?php echo htmlspecialchars($zp ?? 'admin.php'); ?>">На главную</a>
    <div>
        <h2>Зарплата</h2>
    </div>
    <div class="tableFixHead">
        <?php include "zpTab.php"; ?>
    </div>
    <div class="div2">
        <form class="form_row" method="POST">
            <?php echo csrf_token_field(); ?>
            <input type="text" id="id" name="id" readonly size="5" placeholder="№">
            <input type="text" id="date" name="date" required placeholder="Дата и время">
            <input type="text" id="spec" name="spec" required size="20" placeholder="Должность">
            <input type="text" id="name" name="name" required size="40" placeholder="Ф.И.О.">
            <input type="text" id="nachis" name="nachis" required size="10" placeholder="Начислено">
            <input type="text" id="poluch" name="poluch" required size="10" placeholder="Получено">
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
                $('#spec').val($(this).find('td:eq(2)').text());
                $('#name').val($(this).find('td:eq(3)').text());
                $('#nachis').val($(this).find('td:eq(4)').text());
                $('#poluch').val($(this).find('td:eq(5)').text());
            });
        });
        var data = $('#date').val();
        var shablon = /\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}/s;
        if(!shablon.test(data)) {
            $('#date').val(moment().format('YYYY-MM-DD HH:mm:ss'));
        }
        $('div').animate({scrollTop:5000},'50');
    </script>
</body>
</html>