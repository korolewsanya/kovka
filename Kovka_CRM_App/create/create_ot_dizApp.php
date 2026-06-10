<?php
define('APP_START', true);
require_once '../../security.php';
security_headers();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

require_once '../../db_connection.php';

// Укажите абсолютный путь к вашей папке uploads
$targetDir = $_SERVER['DOCUMENT_ROOT'] . "/Kovka_git/kovka/img/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Принимаем файл из поля "image_file"
if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    $fileName = basename($_FILES['image_file']['name']);
    $targetFile = $targetDir . $fileName;
    if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)) {
        echo json_encode(["error" => "Не удалось сохранить файл"]);
        exit;
    }
    // (опционально) можно обновить $_POST['image'] для записи в БД
    // $_POST['image'] = $fileName; 
}


$response = array();

if($_POST['tz'] && $_POST['ot'] && $_POST['date']&& $_POST['cod']&& $_POST['prof']&& $_POST['class_work']&& $_POST['name']&& $_POST['image']){
	$tz = $_POST['tz'];
	$ot = $_POST['ot'];
    $date = $_POST['date'];
    $cod = $_POST['cod'];
    $prof = $_POST['prof'];
    $class_work = $_POST['class_work'];
    $name = $_POST['name'];
	$image = $_POST['image'];
	
	$stmt = $conn->prepare("INSERT INTO `otchet`(`tz`, `otchet`, `date`, `cod`, `prof`, `class_work`, `name`, `image`) VALUES (?,?,?,?,?,?,?,?)");
	$stmt->bind_param("ssssssss",$tz,$ot,$date,$cod,$prof,$class_work,$name,$image);

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
