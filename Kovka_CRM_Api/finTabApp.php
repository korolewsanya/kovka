<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

include "../db_connection.php";

$sql = "SELECT * FROM fin";
if($result = $conn->query($sql))
echo "<table style=' border: 1px solid black; border-collapse: collapse; position: sticky; max-height: 400px;'>
    
	<thead style='border: 1px solid black; border-collapse: collapse'>
    <tr style='border: 1px solid black; border-collapse: collapse'>
    <th style='border: 1px solid black; border-collapse: collapse'>Дата</th>
    <th style='border: 1px solid black; border-collapse: collapse'>Доход</th>
    <th style='border: 1px solid black; border-collapse: collapse'>Расход</th>
    <th style='border: 1px solid black; border-collapse: collapse'>Прибыль</th>
    </tr>
	</thead>";
    foreach($result as $row){
		echo "<tbody style='border: 1px solid black;  border-collapse: collapse'>";
        echo "<tr style='border: 1px solid black; border-collapse: collapse'>";
            echo "<td style='border: 1px solid black; border-collapse: collapse; padding-left: 5px'>" . $row["date"]."</td>";
            echo "<td style='border: 1px solid black; border-collapse: collapse; padding-left: 5px'>" . $row["dohod"]."</td>";
            echo "<td style='border: 1px solid black; border-collapse: collapse; padding-left: 5px'>" . $row["rashod"]."</td>";
            echo "<td style='border: 1px solid black; border-collapse: collapse; padding-left: 5px'>" . $row["prib"]."</td>";
        echo "</tr>";
		echo "</tbody>";
    }
    echo "</table>";

if (isset($_POST['Save'])) {
    if (
         isset($_POST["date"])&& isset($_POST["dohod"])&& isset($_POST["rashod"])
        && isset($_POST["prib"])) {
        $date = $conn->real_escape_string($_POST["date"]);
        $dohod = $conn->real_escape_string($_POST["dohod"]);
        $rashod = $conn->real_escape_string($_POST["rashod"]);
        $prib = $conn->real_escape_string($_POST["prib"]);
        $sql = "INSERT INTO fin (date,dohod,rashod,prib) VALUES ('$date','$dohod','$rashod','$prib')";
        if($conn->query($sql)){
            echo "<script>
            window.location.href = 'fin.php';
            </script>";
        } else{
            echo "Ошибка: " . $conn->error;
        }
    }
}
    if (isset($_POST['Change'])) {
        if (isset($_POST["id"])&& isset($_POST["date"])&& isset($_POST["dohod"])&& isset($_POST["rashod"])
        && isset($_POST["prib"])){
        $id = $conn->real_escape_string($_POST["id"]);
			 $date = $conn->real_escape_string($_POST["date"]);
        $dohod = $conn->real_escape_string($_POST["dohod"]);
    $rashod = $conn->real_escape_string($_POST["rashod"]);
    $prib = $conn->real_escape_string($_POST["prib"]);
        $sql = "UPDATE fin SET date = '$date',dohod = '$dohod', rashod = '$rashod', prib = '$prib' WHERE id = '$id'";
        if($conn->query($sql)){
            echo "<script>
            window.location.href = 'fin.php';
            </script>";
    } else{
        echo "Ошибка: "; //. $conn->error;
    }
        }
        }else{}; 

    if (isset($_POST['Delete'])) { 
    if(isset($_POST["id"])){
    $del = $conn->real_escape_string($_POST["id"]);
    $sql = "DELETE FROM fin WHERE id = '$del'";
    if($conn->query($sql)){
            echo "<script>
            window.location.href = 'fin.php';
            </script>";
    } else{
        echo "Ошибка: "; //. $conn->error;
    }
    }
}


    $conn->close();

    ?>
