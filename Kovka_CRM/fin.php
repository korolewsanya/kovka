<?php
define('APP_START', true);
require_once '../security.php';
security_headers();
csrf_token(); // гарантируем наличие токена в сессии

if(isset($_POST["fin"])){
    $fin = htmlentities($_POST["fin"]);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin2.css" />
    <title>Финансовая отчётность</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
</head>
<body>
    <br>
    <a href="<?php echo htmlspecialchars($fin ?? 'admin.php'); ?>">На главную</a>	
    <br><br>
    <div>
        <h2>Финансовая отчётность</h2>
    </div>
    <div class="tableFixHead">
        <?php include "finTab.php"; ?>
    </div>
    <div class="div2">
        <form class="form_row" method="POST">
            <?php echo csrf_token_field(); ?>
            <input type="text" id="id" name="id" readonly size="5" placeholder="№">
            <input type="text" id="date" name="date" readonly placeholder="Дата и время">
            <input type="number" id="dohod" name="dohod" required size="20" placeholder="Доход">
            <input type="number" id="rashod" name="rashod" required size="40" placeholder="Расход">
            <input type="number" id="prib" name="prib" required size="10" placeholder="Прибыль">
            <input type="submit" id="save" name="Save" value=" Добавить ">
            <input type="submit" id="change" name="Change" value=" Изменить ">
            <input type="submit" id="delete" name="Delete" value=" Удалить ">
        </form>
    </div>
   
    <hr style="height:5px; background:#037FFC; border-top:solid 2px #FC0307; border-bottom:solid 2px #FC0307;">
    
    <p>Ваши доходы:</p>
    <form method="POST">
        <?php echo csrf_token_field(); ?>
        <p>&nbsp;с 
            <input type="date" name="calendar_c">
            &nbsp;по
            <input type="date" name="calendar_po">
            <input type="text" id="fin1" name="fin">
            <input type="submit" id="pok" value="Показать">
        </p>
    </form>
    
    <div class="tableFixHead">
        <?php include "finRasDoh.php"; ?>
    </div>
    
    <hr style="height:5px; background:#037FFC; border-top:solid 2px #FC0307; border-bottom:solid 2px #FC0307;">
    
    <p>Ваши расходы:</p>
    <form method="POST">
        <?php echo csrf_token_field(); ?>
        <p>&nbsp;с 
            <input type="date" name="calendar_c">
            &nbsp;по
            <input type="date" name="calendar_po">
            <input type="text" id="fin2" name="fin">
            <input type="submit" id="pok" value="Показать">
        </p>
    </form>
    
    <div class="tableFixHead">
        <?php include "finRasRash.php"; ?>
    </div>
    
    <hr style="height:5px; background:#037FFC; border-top:solid 2px #FC0307; border-bottom:solid 2px #FC0307;">
     
    <script>
        $(function() {
            $('tr').click(function() {
                var id = $(this).find("td:first").text();
                $('#id').val(id);
                var data = $(this).find('td:eq(1)').text();
                $('#date').val(data);
                var dohod = $(this).find('td:eq(2)').text();
                $('#dohod').val(dohod);
                var rashod = $(this).find('td:eq(3)').text();
                $('#rashod').val(rashod);
                var prib = $(this).find('td:eq(4)').text();
                $('#prib').val(prib);
            });
        });

        $(function() {
            $('#prib').click(function() {
                $('#prib').val($('#dohod').val() - $('#rashod').val());
            });
        });

        $(function() {
            $('#fin1').val("<?php echo htmlspecialchars($fin ?? ''); ?>");
        });
        $(function() {
            $('#fin2').val("<?php echo htmlspecialchars($fin ?? ''); ?>");
        });

        var data = $('#date').val();
        var shablon = /\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}/s;
        if(shablon.test(data)){} else{
            $('#date').val(moment().format('YYYY-MM-DD HH:mm:ss'));
        }
        
        // Прокрутка таблицы вниз
        $('div').animate({scrollTop:5000},'50');
    </script>
</body>
</html>