<?php
 session_start();
 require_once 'connect.php';
 if( !isset($_SESSION['username']) ) {
  header("Location: login.php");
  exit;
 }

date_default_timezone_set('America/New_York');
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
$T_query = "SELECT temperature,ts FROM `device_msg_loggerv2` WHERE deviceID='$ID' ORDER BY ts desc limit 1";
$T_result = mysqli_query($connection, $T_query) or die(mysqli_error($connection));
$T_row = mysqli_fetch_array($T_result,MYSQLI_ASSOC);


$timestamp = $T_row['ts'];
$datetimeFormat = 'Y-m-d H:i:s';
$date = new \DateTime();
$date->setTimestamp($timestamp);
$last_ts = $date->format($datetimeFormat);


?>

<br>

<div align = "center">
	<h1> Welcome <?php echo $username_email?> </h1>

</div>


<div class="container">
  <h2>Your Device Info</h2>
        
  <table class="table">
    <thead>
      <tr>
	<th><center>DeviceID</center></th>
        <th><center>Email</center></th>
        
        <th><center>Desired Temperature <a href='device_update2.php'>change?</a></center></th>
        <th><center>Last Logged Temperature</center></th>
        <th><center>Time Stamp - Most Recent Reading</center></th>
      </tr>
    </thead>
    <tbody>
      <tr class="info">
	        <td><b><center><?php echo $row['deviceID']?></b></center></td>
        <td><b><center><?php echo $email_row['email']?></center></b></td>

        <td><b><center><?php echo $row['desired_temp']?>F</b> </center></td>
        <td><b><center><?php echo $T_row['temperature'] ?>F</b></center></td>
        <td><b><center><?php echo $last_ts ?></b></td>
      </tr>

    </tbody>
  </table>
</div>



<div align="center">
<br>
<p><a href='registerdevice.php'><b>Add a Device</a></b></p>
<p>Dont have one? Buy one here?</p>
<p><a href='email_update.php'><b>Update your email</a></b></p>
<!--<p><a href='device_update2.php'><b>Update a device's temperature notification setting</b></p>-->

 <a href='logout.php'><b>Logout</b></a>


</div>

    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    


</body>
</html>