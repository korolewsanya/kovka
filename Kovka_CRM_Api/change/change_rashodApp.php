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

if($_POST['id'] || $_POST['date'] || $_POST['name'] || $_POST['kup']|| $_POST['izras']|| $_POST['ost']|| $_POST['prise']|| $_POST['itogo']){
	$id = $_POST['id'];
    $date = $_POST['date'];
	$name = $_POST['name'];
	$kup = $_POST['kup'];
    $izras = $_POST['izras'];
	$ost = $_POST['ost'];
	$prise = $_POST['prise'];
    $itogo = $_POST['itogo'];
	
	$stmt = $conn->prepare("UPDATE rashod SET date = ?, name = ?, kup = ?, izras = ?,ost = ?, prise = ?, itogo = ? WHERE id = ?");
	$stmt->bind_param("sssssssi",$date,$name,$kup,$izras,$ost,$prise,$itogo,$id);

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
