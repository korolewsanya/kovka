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

if($_POST['id']|| $_POST['class_work'] || $_POST['prof'] || $_POST['name']|| $_POST['nachis']){
	$id = $_POST['id'];
    $class_work = $_POST['class_work'];
	$prof = $_POST['prof'];
	$name = $_POST['name'];
    $cod = $_POST['cod'];
	
	$stmt = $conn->prepare("UPDATE dostup SET class_work = ?, prof = ?, name = ?, cod = ? WHERE id = ?");
	$stmt->bind_param("ssssi",$class_work,$prof,$name,$cod,$id);

if($stmt->execute() == TRUE){
		$response['error'] = false;
		$response['message'] = "dostup change successfully!";
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
