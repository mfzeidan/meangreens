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

<h3> Logged in as </h3>

<?php
echo $_SESSION['username'];
?>
<br>

<p><b>Devices currently registered to you and their related temperatures</b></p>

<?php 
$username = $_SESSION['username'];
$device_query = "SELECT * FROM `device_mgmt` WHERE username='$username'";
$device_result = mysqli_query($connection, $device_query) or die(mysqli_error($connection));
$row = mysqli_fetch_array($device_result,MYSQLI_ASSOC);
$username_email = $row['username'];
echo " <b>Username </b> ";
echo "<br>";
echo $username_email;
echo "  <br>  ";
$email_query = "SELECT email FROM `user` WHERE username='$username_email'";
$email_result = mysqli_query($connection, $email_query) or die(mysqli_error($connection));
$email_row = mysqli_fetch_array($email_result,MYSQLI_ASSOC);
echo "  <br> <b>email</b>   ";
echo "  <br>   ";
echo $email_row['email'];
echo "<br><br>";
echo "<b>Device</b>";
echo "<br>";
echo $row['deviceID'];
echo "<br><br>";
echo "<b>Desired Temperature</b>";
echo "<br>";
echo $row['desired_temp'];
echo "<br>";
echo "<br>";
echo "<b>Current Device Temperature</b>";
echo "<br>";
//NOTE here i need to be querying from a table that will store all incoming temperatures
echo $row['desired_temp'];
echo "<br>";
echo "<br><br>";
echo "Please <b>register</b> your device here <a href='registerdevice.php'> Register your device</a>";
echo "<br><br>";
echo "If you'd like to <b>update</b> your device's temperature notification limit, please update it here <a href='device_update2.php'> Here</a>";
 ;
?>

<br>

<div align="center">
 <a href='logout.php'>Logout</a>

</div>



<div class="container">
  <h2>Your Device Info</h2>
        
  <table class="table">
    <thead>
      <tr>
        <th>UserName</th>
        <th>Email</th>
        <th>Device</th>
        <th>Desired Temperature</th>
        <th>Current Temperature</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo $username_email?></td>
        <td><?php echo $email_row['email']?></td>
        <td><?php echo $row['deviceID']?></td>
        <td><?php echo $row['desired_temp']?></td>
        <td><?php echo $row['desired_temp'] ?></td>
      </tr>

    </tbody>
  </table>
</div>

<p>Register a Device</p>
<p>Update a device's settings</p>
<p>Logout</p>

    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    
</body>
</html>