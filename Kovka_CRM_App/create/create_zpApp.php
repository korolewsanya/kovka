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

if($_POST['date'] || $_POST['spec'] || $_POST['name']|| $_POST['nachis']|| $_POST['poluch']){
	$date = $_POST['date'];
	$spec = $_POST['spec'];
	$name = $_POST['name'];
    $nachis = $_POST['nachis'];
	$poluch = $_POST['poluch'];
	
	$stmt = $conn->prepare("INSERT INTO `zp`(`date`, `spec`, `name`, `nachis`, `poluch`) VALUES (?,?,?,?,?)");
	$stmt->bind_param("sssss",$date,$spec,$name,$nachis,$poluch);

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
