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

if($_POST['id'] || $_POST['date'] || $_POST['dohod'] || $_POST['rashod']|| $_POST['prib']){
	$id = $_POST['id'];
    $date = $_POST['date'];
	$dohod = $_POST['dohod'];
	$rashod = $_POST['rashod'];
    $prib = $_POST['prib'];
	
	$stmt = $conn->prepare("UPDATE fin SET date = ?, dohod = ?, rashod = ?, prib = ? WHERE id = ?");
	$stmt->bind_param("ssssi",$date,$dohod,$rashod,$prib,$id);

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
