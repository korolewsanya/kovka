<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

include "../db_connection.php";

//Замена кода входа в таблице cod
$response = array();
$x=1;
if($_POST['cod']){
	$cod = $_POST['cod'];
	
	$stmt = $conn->prepare("UPDATE cod SET cod = ? WHERE id = 6");
	$stmt->bind_param("s",$cod);

if($stmt->execute() == TRUE){
		$response['error'] = false;
		$response['message'] = "otchet change successfully!";
	} else{
		$response['error'] = true;
		$response['message'] = "failed\n ".$conn->error;
	}
} else{
	$response['error'] = true;
	$response['message'] = "Insufficient parameters";
}
echo json_encode($response);
?>