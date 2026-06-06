<?php
define('APP_START', true);
require_once '../security.php';
security_headers();
// Гарантируем наличие токена (вызываем csrf_token() для инициализации)
csrf_token();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin2.css" />
    <title>Управление доступом</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
</head>
<body>
   <br>
   <a href="admin.php">На главную</a>	
   <div>
        <h2>Управление доступом</h2>
   </div>
   <div class="tableFixHead">
       <?php include "dostupTab.php"; ?>
   </div>
   <div class="div2">
       <form class="form_row" method="POST">
           <?php echo csrf_token_field(); ?>
           <input type="text" id="id" name="id" readonly size="5" placeholder="№" >
           <input type="text" id="class_work" name="class_work" required placeholder="Классификация">
           <input type="text" id="prof" name="prof" required size="20" placeholder="Должность">
           <input type="text" id="name" name="name" required size="40" placeholder="Ф.И.О.">
           <input type="text" id="cod" name="cod" required size="10" placeholder="Код доступа">
           <input type="submit" id="save" name="Save" value=" Добавить "/>
           <input type="submit" id="change" name="Change" value=" Изменить "/>
           <input type="submit" id="delete" name="Delete" value=" Удалить "/>
       </form>
   </div>
   <script>
        $(function() {
            $('tr').click(function() {
                $('#id').val($(this).find("td:first").text());
                $('#class_work').val($(this).find('td:eq(1)').text());
                $('#prof').val($(this).find('td:eq(2)').text());
                $('#name').val($(this).find('td:eq(3)').text());
                $('#cod').val($(this).find('td:eq(4)').text());
            });
        });
        $('div').animate({scrollTop:5000},'50');
    </script>
</body>
</html>