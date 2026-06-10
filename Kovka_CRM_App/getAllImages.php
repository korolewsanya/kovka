<?php
//Constants for database connection
 define('DB_HOST','localhost');
 define('DB_USER','root');
 define('DB_PASS','');
 define('DB_NAME','images');
 
 //We will upload files to this folder
 //So one thing don't forget, also create a folder named uploads inside your project folder i.e. MyApi folder
 define('UPLOAD_PATH', 'uploads/');
 
 //connecting to database 
 $conn = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die('Unable to connect');
 
 
 //An array to display the response
 $response = array();
 
 //если вызов является вызовом API
 if(isset($_GET['apicall'])){
 
 //переключение вызова API
 switch($_GET['apicall']){
 
 //если это вызов загрузки, мы загрузим изображение
 case 'uploadpic':
 
 //сначала подтверждаем, что у нас есть изображение и теги в параметре запроса
 if(isset($_FILES['pic']['name']) && isset($_POST['tags'])){
 //загрузка файла и сохранение его в базе данных, а также
 try{
 move_uploaded_file($_FILES['pic']['tmp_name'], UPLOAD_PATH . $_FILES['pic']['name']);
 $stmt = $conn->prepare("INSERT INTO images (image, tags) VALUES (?,?)");
 $stmt->bind_param("ss", $_FILES['pic']['name'],$_POST['tags']);
 if($stmt->execute()){
 $response['error'] = false;
 $response['message'] = 'Файл успешно загружен';
 }else{
 throw new Exception("Could not upload file");
 }
 }catch(Exception $e){
 $response['error'] = true;
 $response['message'] = 'Could not upload file';
 }
 
 }else{
 $response['error'] = true;
 $response['message'] = "Required params not available";
 }
 
 break;
 
 //в этом вызове мы получим все изображения
 case 'getpics':
 
 //getting server ip for building image url 
 $server_ip = gethostbyname(gethostname());
 
 //query to get images from database
 $stmt = $conn->prepare("SELECT id, image, tags FROM images");
 $stmt->execute();
 $stmt->bind_result($id, $image, $tags);
 
 $images = array();
 
 // получение всех изображений из базы данных
  //и помещаем его в массив
 while($stmt->fetch()){
 $temp = array();
 $temp['id'] = $id; 
 $temp['image'] = 'http://' . $server_ip . '/Загрузка изображений на сервер/Upload_Image_to_Server_in_Db/'. UPLOAD_PATH . $image; 
 $temp['tags'] = $tags; 
 
 array_push($images, $temp);
 }
 
 //pushing the array in response 
 $response['error'] = false;
 $response['images'] = $images; 
 break; 
 
 default: 
 $response['error'] = true;
 $response['message'] = 'Invalid api call';
 }
 
 }else{
 header("HTTP/1.0 404 Not Found");
 echo "<h1>Нет</h1>";
 echo "Запрошенная вами страница не найдена почему-то";
 exit();
 }
 
 //displaying the response in json 
 header('Content-Type: application/json');
 echo json_encode($response,JSON_UNESCAPED_UNICODE);
 

