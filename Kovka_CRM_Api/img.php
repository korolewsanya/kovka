<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кованые изделия</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { margin: 0; padding: 0; min-height: 100vh; background-color: white; }
        .fullscreen-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-position: center; background-repeat: no-repeat;
            background-size: auto 100%; background-color: white;
        }
    </style>
</head>
<body>

<?php
include '../db_connection.php';

$img = "";
$sql = "SELECT img FROM img WHERE id = '1' LIMIT 1";
if($result = $conn->query($sql)){
    if($row = $result->fetch_assoc()){
        $userid = $row["img"];
        
        $stmt = $conn->prepare("SELECT image FROM zakaz WHERE image = ? LIMIT 1");
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res && $r = $res->fetch_assoc()){
            $img = $r["image"];
        }
        $stmt->close();
    }
}

if (empty($img)) {
    $img = "мангал1.jpg";
}

$imagePath = "../img/" . htmlspecialchars($img, ENT_QUOTES, 'UTF-8');
?>

<div class="fullscreen-bg" style="background-image: url('<?php echo $imagePath; ?>');"></div>

</body>
</html>