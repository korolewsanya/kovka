<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin2.css" />
    <title>Финансовая отчётность</title>
</head>
<body style="margin: 0;">
<?php
		if(isset($_POST["fin"])){
    $fin = htmlentities($_POST["fin"]);
}
		?>
   
 <div class="tableFixHead">
       <?php 
       include "finTabApp.php";
       ?>
    </div>

    <p style="margin: 10px;">Ваши доходы:</p>
    <form method="POST">
    <p>&nbsp;с 
           <input type="date" name="calendar_c">
           &nbsp;
           по
           <input type="date" name="calendar_po">
           <br>
           <br>
            <input style="margin: 10px;" type="submit" id="pok" value="Показать">
            </p>
            </form>
    
     <div class="tableFixHead">
    	<?php 
       include __DIR__ . '/../Kovka_CRM/finRasDoh.php';
       ?>
    </div>
    
     <hr style="height:5px; background:#037FFC; border-top:solid 2px #FC0307; border-bottom:solid 2px #FC0307;">
    
    <p style="margin: 10px;">Ваши расходы:</p>
    <form method="POST">
    <p>&nbsp;с 
           <input type="date" name="calendar_c">
           &nbsp;
           по
           <input type="date" name="calendar_po">
           <br>
           <br>
            <input style="margin: 10px;" type="submit" id="pok" value="Показать">
            </p>
            </form>
    
     <div class="tableFixHead">
    	<?php 
       include __DIR__ . '/../Kovka_CRM/finRasRash.php';
       ?>
    </div>
    
</body>
</html>