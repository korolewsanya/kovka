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

if($_POST['date'] || $_POST['izdelie'] || $_POST['image']|| $_POST['dlina']|| $_POST['shirina']|| $_POST['visota']|| $_POST['prise'] || $_POST['pay']|| $_POST['proces']|| $_POST['name']|| $_POST['tel']|| $_POST['email']|| $_POST['coment']){
	$data = $_POST['date'];
	$izdelie = $_POST['izdelie'];
	$image = $_POST['image'];
    $dlina = $_POST['dlina'];
	$shirina = $_POST['shirina'];
	$visota = $_POST['visota'];
    $prise = $_POST['prise'];
	$pay = $_POST['pay'];
	$proces = $_POST['proces'];
    $name = $_POST['name'];
	$tel = $_POST['tel'];
	$email = $_POST['email'];
    $coment = $_POST['coment'];
	
	$stmt = $conn->prepare("INSERT INTO `zakaz`(`date`, `izdelie`, `image`, `Dlina`, `Shirina`, `Visota`, `Prise`, `Pay`, `Proces`, `Name`, `Tel`, `Email`, `Coment`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
	$stmt->bind_param("sssssssssssss",$data,$izdelie,$image,$dlina,$shirina,$visota,$prise,$pay,$proces,$name,$tel,$email,$coment);

if($stmt->execute() == TRUE){
		$response['error'] = false;
		$response['message'] = "course created successfully!";
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
