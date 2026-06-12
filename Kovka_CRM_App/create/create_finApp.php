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

if($_POST['date'] || $_POST['dohod'] || $_POST['rashod']|| $_POST['prib']){
    $date = $_POST['date'];
	$dohod = $_POST['dohod'];
	$rashod = $_POST['rashod'];
    $prib = $_POST['prib'];
	
	$stmt = $conn->prepare("INSERT INTO `fin`( `date`, `dohod`, `rashod`, `prib`) VALUES (?,?,?,?)");
	$stmt->bind_param("ssss",$date,$dohod,$rashod,$prib);

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
