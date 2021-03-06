<html>
<head>

<style>
 body { background:#b3ffb3 !important; }
</style>

	<title>Temperature control Homepage</title>

	
<!-- Latest compiled and minified CSS -->
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
<link rel="stylesheet" href="styles.css" >
 

<?php  //Start the Session
session_start();
 require('connect.php');

 if( isset($_SESSION['username']) ) {
  header("Location: home.php");
  exit;
 }


//3. If the form is submitted or not.
//3.1 If the form is submitted
if (isset($_POST['username']) and isset($_POST['password'])){
//3.1.1 Assigning posted values to variables.
$username = $_POST['username'];
$password = md5($_POST['password']);
//3.1.2 Checking the values are existing in the database or not
$query = "SELECT * FROM `user` WHERE username='$username' and password='$password'";
 
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$count = mysqli_num_rows($result);
//3.1.2 If the posted values are equal to the database values, then session will be created for the user.
if ($count == 1){
$_SESSION['username'] = $username;
}else{
//3.1.3 If the login credentials doesn't match, he will be shown with an error message.
$fmsg = "Invalid Login Credentials.";
}
}
//3.1.4 if the user is logged in Greets the user with message
if (isset($_SESSION['username'])){
 header("Location: home.php");


$username = $_SESSION['username'];
echo "Hi " . $username . "
";
echo "<br>";
echo "This is the Members Area
";
echo "<a href='logout.php'>Logout</a>";



echo "<br><br>";

echo "<p>Devices currently registered to you and their related temperatures</p>";

$device_query = "SELECT * FROM `device_mgmt` WHERE username='$username'";
$device_result = mysqli_query($connection, $device_query) or die(mysqli_error($connection));



$row = mysqli_fetch_array($device_result,MYSQLI_ASSOC);

$username_email = $row['username'];

echo "  <br> <b>username </b><br>   ";
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




 
}else{

//3.2 When the user visits the page first time, simple login form will be displayed.
?>
<html>
<head>
	<title>User Temperature Control</title>


  <style>
  .modal-header, h4, .close {
      background-color: #5cb85c;
      color:white !important;
      text-align: center;
      font-size: 30px;
  }
  .modal-footer {
      background-color: #f9f9f9;
  }
  </style>




	
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
 
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
 
<link rel="stylesheet" href="styles.css" >
 
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

 <!-- Navigation -->
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

                    <li class="navbar-right">
                        <a href='logout.php'>Logout</a>
                    </li>

                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
<br><br><br>
 
<div class="container">
      <form class="form-signin" method="POST">
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <h2 class="form-signin-heading">Enter Temp Sensor Portal</h2>
	<p>Please login to control your personal temperature sensor</p>
        <div class="input-group">
	  <span class="input-group-addon" id="basic-addon1">@</span>
	  <input type="text" name="username" class="form-control" placeholder="Username" required>
	</div>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
	<br>
	<p> <b>Don't have an account? Register below </b> <p/>
        <a class="btn btn-lg btn-primary btn-block" href="register.php">Register</a>


      </form>
</div>

<div class="container">
  <h2>Modal Login Example</h2>
  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-default btn-lg" id="myBtn">Login</button>

  <!-- Modal -->

      <form class="form-signin" method="POST">
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>

  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4><span class="glyphicon glyphicon-lock"></span> Login</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form">
            <div class="form-group">
	<label for="usrname"><span class="glyphicon glyphicon-user"></span> Username</label>
             	  <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="form-group">
              <!--<label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Password</label>-->
              <!--<input type="text" class="form-control" id="psw" placeholder="Enter password">-->
	<label for="inputPassword"><span class="glyphicon glyphicon-eye-open"></span> Password</label>

		<label for="inputPassword" class="sr-only"><span class="glyphicon glyphicon-eye-open"></span> Password</label>
		
       		 <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>


            </div>

              <button type="submit" class="btn btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Login</button>
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          <p>Not a member? <a href="register.php">Sign Up</a></p>
          <p>Forgot <a href="#">Password?</a></p>
        </div>
      </div>
      
    </div>
  </div> 
</div>
 
<script>
$(document).ready(function(){
    $("#myBtn").click(function(){
        $("#myModal").modal();
    });
});
</script>


 
</body>
 
</html>
<?php } ?>