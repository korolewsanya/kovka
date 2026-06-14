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

if($_POST['spec'] || $_POST['name'] || $_POST['tel']|| $_POST['email']|| $_POST['adres']|| $_POST['data']|| $_POST['proch']){
	$spec = $_POST['spec'];
	$name = $_POST['name'];
	$tel = $_POST['tel'];
    $email = $_POST['email'];
	$adres = $_POST['adres'];
	$data = $_POST['data'];
    $proch = $_POST['proch'];
	
	$stmt = $conn->prepare("INSERT INTO `workes`(`spec`, `name`, `tel`, `email`, `adres`, `data`, `proch`) VALUES (?,?,?,?,?,?,?)");
	$stmt->bind_param("sssssss",$spec,$name,$tel,$email,$adres,$data,$proch);

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
