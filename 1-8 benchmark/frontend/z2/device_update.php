<?php
	require('connect.php');
 if( !isset($_SESSION['username']) ) {
  header("Location: login.php");
  exit;
 }
    // If the values are posted, insert them into the database.
    if (isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
	$email = $_POST['email'];
        $password = $_POST['password'];
 
        $query = "update `device_mgmt` set desired_temp =$password where deviceID = $email and username = '$username' ";
        $result = mysqli_query($connection, $query);
        if($result){
            $smsg = "updated correctly.";
        }else{
		echo "<center>that device has not been registered. Please register it before trying to update it's respective temperature setting </center>";
            $fmsg ="User Registration Failed";
        }
    }
    ?>



<html>
<head>
	<title>Update your device's settings</title>
	
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
 
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
 
<link rel="stylesheet" href="styles.css" >
 
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
 
<div class="container">
      <form class="form-signin" method="POST">
      
      <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <h2 class="form-signin-heading">Update Device</h2>
	<p>Please enter your username, deviceID and new temperature notification limit</p>
        <div class="input-group">
	  <span class="input-group-addon" id="basic-addon1">@</span>
	  <input type="text" name="username" class="form-control" placeholder="Username" required>
	</div>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="text" name="email" id="inputEmail" class="form-control" placeholder="DeviceID" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="text" name="password" id="inputPassword" class="form-control" placeholder="Temperature" required>
        <div class="checkbox">

        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Update</button>
        <a class="btn btn-lg btn-primary btn-block" href="login.php">Return to your homepage</a>
      </form>
</div>
 
</body>
 
</html>