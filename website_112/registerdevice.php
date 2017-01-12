
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
            $fmsg ="User Registration Failed. This device has already been registered or you are entering letters for DeviceID or temperature!";
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>


<body>
<style type="text/css">
   body { background:#b3ffb3 !important; } /* Adding !important forces the browser to overwrite the default style applied by Bootstrap */
</style>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <img src="vs_text_logo.png" width="300" height="40" alt="">
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="nav-link active">
                        <a href="#">Home</a>
                    </li>
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="product_page.html#">Buy</a>
                    </li>

                    <li>
                        <a href='logout.php'>Logout</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

            </div>
        </div>
    </div>
    <!-- /.container -->

<br><br><br>

 
<div class="container">
      <form class="form-signin" method="POST">
      
      <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php header('Location: home.php');} ?>
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <h2 class="form-signin-heading">Please Register your device</h2>

	  <!--<span class="input-group-addon" id="basic-addon1">@</span>-->
	  <!--<input type="text" name="username" class="form-control" placeholder="Username" required>-->

        <label for="inputEmail" class="sr-only">Email address</label>

        <input type="text" name="email" id="inputEmail" class="form-control" placeholder="DeviceID" required autofocus>
	        
	<label for="inputPassword" class="sr-only">Password</label>

        <div class="input-group">
	  <span class="input-group-addon align-right" id="basic-addon1"><a href="#" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="Enter the hottest degree (F) you'd like your device to get before notifying you!"><right><font size="4"><b>?</b></font></right></a></span>
	  <input type="text" name="password" id="inputPassword" class="form-control" placeholder="Temperature (F)" required autofocus>
  
	</div>

        <!--<input type="text" name="password" id="inputPassword" class="form-control" placeholder="Temperature" required>-->
<br>

<div class="container">
 

</div>

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
</script>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Register Your Device</button>
        <a class="btn btn-lg btn-primary btn-block" href="home.php">Return to your homepage</a>
      </form>
</div>
 
</body>
 
</html>