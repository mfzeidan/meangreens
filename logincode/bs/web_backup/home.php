<?php
 session_start();
 require_once 'connect.php';
 if( !isset($_SESSION['username']) ) {
  header("Location: login.php");
  exit;
 }
?>

<!DOCTYPE html>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome - <?php echo $userRow['userEmail']; ?></title>
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<div align = "center">
	<h1> This is the members area </h1>
</div>



<?php 
$username = $_SESSION['username'];
$device_query = "SELECT * FROM `device_mgmt` WHERE username='$username'";
$device_result = mysqli_query($connection, $device_query) or die(mysqli_error($connection));
$row = mysqli_fetch_array($device_result,MYSQLI_ASSOC);



$username_email = $row['username'];

$email_query = "SELECT email FROM `user` WHERE username='$username_email'";
$email_result = mysqli_query($connection, $email_query) or die(mysqli_error($connection));
$email_row = mysqli_fetch_array($email_result,MYSQLI_ASSOC);

$ID = $row['deviceID'];

$T_query = "SELECT temperature FROM `device_msg_loggerv2` WHERE deviceID='$ID' ORDER BY ts desc limit 1";
$T_result = mysqli_query($connection, $T_query) or die(mysqli_error($connection));
$T_row = mysqli_fetch_array($T_result,MYSQLI_ASSOC);




?>

<br>




<div class="container">
  <h2>Your Device Info</h2>
        
  <table class="table">
    <thead>
      <tr>
        <th>UserName</th>
        <th>Email</th>
        <th>Device</th>
        <th>Desired Temperature</th>
        <th>Last Logged Temperature</th>
      </tr>
    </thead>
    <tbody>
      <tr class="info">
        <td><b><?php echo $username_email?></b></td>
        <td><b><?php echo $email_row['email']?></b></td>
        <td><b><?php echo $row['deviceID']?></b></td>
        <td><b><?php echo $row['desired_temp']?></b></td>
        <td><b><?php echo $T_row['temperature'] ?></b></td>
      </tr>

    </tbody>
  </table>
</div>


<div align="center">
<br>
<p><a href='registerdevice.php'><b>Register a Device</a></b></p>
<p><a href='email_update.php'><b>Update your email</a></b></p>
<p><a href='device_update2.php'><b>Update a device's settings</b></p>

 <a href='logout.php'><b>Logout</b></a>


</div>

    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    


</body>
</html>