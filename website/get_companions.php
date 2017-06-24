<?php
  require_once __DIR__ . '/connect.php';
  $connection = new CONNECT();
  $get_companions = "SELECT * FROM companions";
  $companions = mysqli_query($connection->connect(), $get_companions);
  $companionsDisplay = array();
  while ($companion = mysqli_fetch_array($companions)) {
    echo "+";
    echo $companion["city"] . ":";
    echo $companion["item"] . ":";
    echo $companion["email"] . ":";
    echo $companion["total"] . ":";
    echo $companion["new"];
  }
 ?>
