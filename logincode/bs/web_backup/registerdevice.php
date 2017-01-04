
<?php
	 session_start();
	require('connect.php');

 if( !isset($_SESSION['username']) ) {
  header("Location: login.php");
  exit;
 }
	$sesh = $_SESSION['username'];
	//echo $sesh;


    // If the values are posted, insert them into the database.
    if (isset($_POST['password']) && isset($_POST['password'])){
        $username = $_POST['username'];
	$email = $_POST['email'];
        $password = $_POST['password'];
 
        $query = "INSERT INTO `device_mgmt` (username, deviceID, desired_temp) VALUES ('$sesh', '$email','$password' )";
        $result = mysqli_query($connection, $query);
        if($result){
            $smsg = "User Created Successfully.";
        }else{
            $fmsg ="User Registration Failed";
        }
    }
    ?>


<html>
<head>
	<title>User Registeration Using PHP & MySQL</title>
	
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
        <h2 class="form-signin-heading">Please Register your device</h2>

	  <!--<span class="input-group-addon" id="basic-addon1">@</span>-->
	  <!--<input type="text" name="username" class="form-control" placeholder="Username" required>-->

        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="text" name="email" id="inputEmail" class="form-control" placeholder="DeviceID" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="text" name="password" id="inputPassword" class="form-control" placeholder="Temperature" required>
        <div class="checkbox">

        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Register Your Device</button>
        <a class="btn btn-lg btn-primary btn-block" href="login.php">Return to your homepage</a>
      </form>
</div>
 
</body>
 
</html>