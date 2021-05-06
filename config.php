<?php

$conn = new mysqli('localhost', 'root', '', 'intouch-api');

if($conn->connect_error){
  die ("Connection Error" . $conn->connect_error);
}

?>


