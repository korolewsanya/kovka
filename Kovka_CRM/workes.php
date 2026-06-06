<?php
define('APP_START', true);
require_once '../security.php';
security_headers();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin2.css" />
    <title>Сотрудники</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
</head>
<body>
   <br>
   <a href="admin.php">На главную</a>
   <br><br>
   <div class="tableFixHead">
       <?php include "workesTab.php"; ?>
   </div>
   
   <form method="POST">
       <?php echo csrf_token_field(); ?>
       <input type="text" id="id" name="id" readonly placeholder="№"><br>
       <input type="text" id="spec" name="spec" placeholder="Должность"><br>
       <textarea id="name" name="name" required placeholder="Ф.И.О."></textarea><br>
       <input type="tel" id="tel" name="tel" required placeholder="Телефон"><br>
       <input type="text" id="email" name="email" placeholder="Email"><br>
       <textarea id="adres" name="adres" placeholder="Адрес"></textarea><br>
       <input type="text" id="data" name="data" placeholder="Дата рождения"><br>
       <textarea id="proch" name="proch" placeholder="Прочее"></textarea><br>
       <input type="submit" id="save" name="Save" value=" Добавить "/>
       <input type="submit" id="change" name="Change" value=" Изменить "/>
       <input type="submit" id="delete" name="Delete" value=" Удалить "/>
   </form>
   
   <script>
        $(function() {
            $('tr').click(function() {
                $('#id').val($(this).find("td:first").text());
                $('#spec').val($(this).find('td:eq(1)').text());
                $('#name').val($(this).find('td:eq(2)').text());
                $('#tel').val($(this).find('td:eq(3)').text());
                $('#email').val($(this).find('td:eq(4)').text());
                $('#adres').val($(this).find('td:eq(5)').text());
                $('#data').val($(this).find('td:eq(6)').text());
                $('#proch').val($(this).find('td:eq(7)').text());
            });
        });
        $('div').animate({scrollTop:5000},'50');
    </script>
</body>
</html>