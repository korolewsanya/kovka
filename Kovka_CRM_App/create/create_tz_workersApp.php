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

if($_POST['tz']&& $_POST['date']&& $_POST['cod']&& $_POST['prof']&& $_POST['class_work']&& $_POST['name']){
	$tz = $_POST['tz'];
    $date = $_POST['date'];
    $cod = $_POST['cod'];
    $prof = $_POST['prof'];
    $class_work = $_POST['class_work'];
    $name = $_POST['name'];
	
	$stmt = $conn->prepare("INSERT INTO `otchet`(`tz`, `date`, `cod`, `prof`, `class_work`, `name`) VALUES (?,?,?,?,?,?)");
	$stmt->bind_param("ssssss",$tz,$date,$cod,$prof,$class_work,$name);

if($stmt->execute() == TRUE){
		$response['error'] = false;
		$response['message'] = "zadachi created successfully!";
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
