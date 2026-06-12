<?php

$img = "не определено";
if (isset($_POST["img"])) {

  $img = $_POST["img"];
}
  include '../db_connection.php';
  $sql = "UPDATE img SET img = '$img' WHERE id = '1'";
  $result = $conn->query($sql);
  $conn->close();

?>