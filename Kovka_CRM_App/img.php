<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кованые изделия</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #000;
        }
        
        .fullscreen-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: auto 100%; /* Высота на весь экран, ширина пропорционально */
            background-color: #000;
        }
    </style>
</head>
<body>

<?php
include '../db_connection.php';

$sql = "SELECT * FROM img";
if($result = $conn->query($sql)){
    while($row = $result->fetch_array()){
        $userid = $row["img"];
    }
}

$sql = "SELECT * FROM zakaz WHERE image = '$userid' ";
if($result = $conn->query($sql)){
    foreach ($result as $row) {
        $img = $row["image"];
    }
}

if (empty($img)) {
    $img = "мангал1.jpg";
}

$imagePath = "../img/" . $img;
?>

<div class="fullscreen-bg" style="background-image: url('<?php echo $imagePath; ?>');"></div>

</body>
</html>