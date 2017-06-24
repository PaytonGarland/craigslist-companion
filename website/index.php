<html>
  <head>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <link rel="icon" href="craigslist-companion-logo.png">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />
    <link href="fonts.css" rel="stylesheet" type="text/css" media="all" />
    <title>Craigslist Companion</title>
  </head>
  <body>
    <div id="header">
      <img src="craigslist-companion-logo.png" height="40px" width="40px"/>
      <h1>CRAIGSLIST COMPANION</h1>
    </div>

    <?php
      if (isset($_POST["city"]) && isset($_POST["item"]) && isset($_POST["email"])) {
        require_once __DIR__ . '/connect.php';
        $connection = new CONNECT();
        $validate = "SELECT * FROM companions";

        $email = $_POST["email"];
        $item = $_POST["item"];
        $city = $_POST["city"];

        $validation = mysqli_query($connection->connect(), $validate);
        $isDuplicate = false;
        while($companion = mysqli_fetch_array($validation)) {
          if ($companion["email"] == $email) {
            $isDuplicate = true;
          }
        }

        if ($isDuplicate == false) {
          $get_companions = "INSERT INTO companions (email, item, city) VALUES ('$email', '$item', '$city')";
          $companions = mysqli_query($connection->connect(), $get_companions);
          ?>
          <form id="companion" action="index.php" method="POST">
            <p>Thank you for creating a companion!  We will email you as soon as your companion discovers a new posting.</p>
            <input type="submit" value="BACK"><br>
          </form>
        <?php
        } else {
          ?>
          <form id="companion" action="index.php" method="POST">
            <p>Oh no!  It looks like you already have a companion.  Please remove your old companion before creating a new one.</p>
            <input type="submit" value="BACK"><br>
          </form>
          <?php
        }
      } else if (isset($_POST["email"])){
        require_once __DIR__ . '/connect.php';
        $connection = new CONNECT();
        $email = $_POST["email"];
        $check = "SELECT * FROM companions WHERE email = '$email'";
        $checkQuery = mysqli_query($connection->connect(), $check);
        while($companion = mysqli_fetch_array($checkQuery)) {
          $link = "https://" . $companion["city"] . ".craigslist.org/search/sss?sort=date&query=" . $companion["item"];
          $new = $companion["new"];
        }
        $reset = "UPDATE companions SET new='0' WHERE email = '$email'";
        $companions = mysqli_query($connection->connect(), $reset);
        ?>
        <form id="companion" action="index.php" method="POST">
          <p>Welcome back! There have been <?php echo $new ?> new postings since you last checked with your companion.  <br>Click <a href=" <?php echo $link ?>" target="_blank">here</a> to see!</p>
          <input type="submit" value="BACK"><br>
        </form>
        <?php

      } else {
     ?>
    <form id="companion" action="index.php" method="POST">
      <p style="font-size: 20px; font-weight: bold;">Create a companion.<br> After that, let us do the work!</p>
      CITY:<br>
      <input type="text" name="city"><br>
      ITEM:<br>
      <input type="text" name="item"><br>
      EMAIL:<br>
      <input type="text" name="email"><br><br>
      <input type="submit" value="CREATE COMPANION"><br>
    </form>
    <form id="companion" action="index.php" method="POST">
      EMAIL:<br>
      <input type="text" name="email"><br><br>
      <input type="submit" value="CHECK COMPANION" style="margin: 0;"><br>
    </form>
    <?php
      }
     ?>



  </body>
</html>
