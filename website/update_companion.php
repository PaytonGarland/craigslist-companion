<?php
  if (isset($_POST["email"]) && isset($_POST["total"]) && isset($_POST["new"])) {
    $email = $_POST["email"];
    $total = $_POST["total"];
    $new = $_POST["new"];
    require_once __DIR__ . '/connect.php';
    $connection = new CONNECT();
    $get_companions = "UPDATE companions SET total='$total', new='$new' WHERE email = '$email'";
    $companions = mysqli_query($connection->connect(), $get_companions);
  }
 ?>
