<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title> Вход </title>
</head>
	<body style="text-align: center">
	
	<form method="POST">
<input id="nabor" type="text" name="nabor" style="text-align:center; margin-top: 80px;" autofocus><br><br>
<input type="submit" value=" Войти "
    style="border-radius: 100px;
    background-color:forestgreen;
    margin-top: 100px;
    font-size: 20px;
    font-weight: bold;"/>
    </form>
    <div style="display: none;">
    <?php
			$nabor = "не определено";
		if(isset($_POST["nabor"])){
  
    $nabor = $_POST["nabor"];
}
$link = mysqli_connect("localhost", "root", "", "kovka");
mysqli_set_charset($link, "utf8");
if ($link == false){                                                                                                               
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
} else {
$result = mysqli_query($link, "SELECT `class_work` FROM `otchet` WHERE `cod` = '$nabor'");
$user = mysqli_fetch_assoc($result); 
echo $user['class_work'];
		if ($user['class_work'] == 1) {
			$sql = "UPDATE cod SET cod = '$nabor' WHERE id = 6";
    $link->query($sql);
			header("Location: worker.php?role=admin");
		} else if($user['class_work'] == 2){
			$sql = "UPDATE cod SET cod = '$nabor' WHERE id = 6";
    $link->query($sql);
			header("Location: worker.php?role=diz");
			}
	else if($user['class_work'] == 3){
		$sql = "UPDATE cod SET cod = '$nabor' WHERE id = 6";
    $link->query($sql);
			header("Location: worker.php?role=svar");
			}
	else if($user['class_work'] == 4){
		$sql = "UPDATE cod SET cod = '$nabor' WHERE id = 6";
    $link->query($sql);
			header("Location: worker.php?role=slesar");
			}
	else if($user['class_work'] == 5){
		$sql = "UPDATE cod SET cod = '$nabor' WHERE id = 6";
    $link->query($sql);
			header("Location: worker.php?role=color");
			}
	else if($user['class_work'] == 6){
		$sql = "UPDATE cod SET cod = '$nabor' WHERE id = 6";
    $link->query($sql);
			header("Location: worker.php?role=car");
			}
}
		?>
		</div>
</body>
</html>