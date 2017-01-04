<html>
<head>
	<title>Temperature control Homepage</title>
	
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
 
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
 
<link rel="stylesheet" href="styles.css" >
 

<?php  //Start the Session
session_start();
 require('connect.php');
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
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <h2 class="form-signin-heading">Enter Temp Sensor Portal</h2>
	<p>Please login to control your personal temperature sensor</p>
        <div class="input-group">
	  <span class="input-group-addon" id="basic-addon1">@</span>
	  <input type="text" name="username" class="form-control" placeholder="Username" required>
	</div>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>

        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
	<br>
	<p> <b>Don't have an account? Register below </b> <p/>
        <a class="btn btn-lg btn-primary btn-block" href="register.php">Register</a>


      </form>
</div>
 
</body>
 
</html>
<?php } ?>