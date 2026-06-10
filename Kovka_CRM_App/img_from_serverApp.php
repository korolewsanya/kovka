<?php

include "podklDB_images.php";

//создание запроса
$stmt = $conn->prepare("SELECT image, tags, CONCAT(path, image), id FROM images;");
//выполнение запроса
$stmt->execute();

//привязка результатов к запросу
$stmt->bind_result($image, $tags, $path, $id);
$products = array(); 

//проходя через все результаты
while($stmt->fetch()){
$temp = array(); 
$temp['image'] = $image; 
$temp['tags'] = $tags; 
$temp['path'] = $path;
$temp['id'] = $id; 
array_push($products, $temp);
}
 
//TRIM(path,image) CONCAT(path, image)
//displaying the result in json format 
echo json_encode($products,JSON_UNESCAPED_UNICODE);
?>
