<?php
define('APP_START', true);
require_once '../security.php';
security_headers();

include "../db_connection.php";

$sql_query = "SELECT * FROM `zakaz`";

$r = mysqli_query($conn, $sql_query);
$data = array();

if (mysqli_num_rows($r) > 0)
{
while($row = mysqli_fetch_assoc($r))
$data[] = $row;
}
echo (json_encode(array("zakaz" => $data),JSON_UNESCAPED_UNICODE));
?>