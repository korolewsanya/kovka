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
 
 // массив для отображения ответа
 $response = array();
 // в строке ниже мы проверяем, является ли отправленный параметр идентификатором или нет.
 if($_POST['id']){
     // если параметр отправляется от идентификатора пользователя, то
      // мы будем искать элемент по определенному идентификатору.
     $id = $_POST['id'];
        //в нижней строке мы выбираем детали курса с указанным ниже идентификатором.
     $stmt = $conn->prepare("DELETE FROM fin WHERE id = ?");
     $stmt->bind_param("s",$id);
     $result = $stmt->execute();
   
 } else{
      // if the user donot adds any parameter while making request
      // then we are displaying the error as insufficient parameters.
      $response['error'] = true;
      $response['message'] = "Insufficient Parameters";
 }
 // наконец мы печатаем
  // все данные в нижней строке.
 echo json_encode($response);
?>