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

if($_POST['class_work'] || $_POST['prof'] || $_POST['name']|| $_POST['nachis']){
	$class_work = $_POST['class_work'];
	$prof = $_POST['prof'];
	$name = $_POST['name'];
    $cod = $_POST['cod'];
	
	$stmt = $conn->prepare("INSERT INTO `dostup`(`class_work`, `prof`, `name`, `cod`) VALUES (?,?,?,?)");
	$stmt->bind_param("ssss",$class_work,$prof,$name,$cod);

if($stmt->execute() == TRUE){
		$response['error'] = false;
		$response['message'] = "fin created successfully!";
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
