<?php
define('APP_START', true);
require_once '../../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}
//csrf_check();

require_once '../../db_connection.php';

$response = array();

if($_POST['id'] || $_POST['date'] || $_POST['spec'] || $_POST['name']|| $_POST['nachis']|| $_POST['poluch']){
	$id = $_POST['id'];
    $date = $_POST['date'];
	$spec = $_POST['spec'];
	$name = $_POST['name'];
    $nachis = $_POST['nachis'];
	$poluch = $_POST['poluch'];
	
	$stmt = $conn->prepare("UPDATE zp SET date = ?, spec = ?, name = ?, nachis = ?,poluch = ? WHERE id = ?");
	$stmt->bind_param("sssssi",$date,$spec,$name,$nachis,$poluch,$id);

if($stmt->execute() == TRUE){
		$response['error'] = false;
		$response['message'] = "zakaz change successfully!";
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
