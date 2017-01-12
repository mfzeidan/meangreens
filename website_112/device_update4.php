<?php
	session_start();
	require('connect.php');
 if( !isset($_SESSION['username']) ) {
  header("Location: login.php");
  exit;
 }
	//echo $_SESSION['username'];
	$sesh = $_SESSION['username'];

$device_query = "SELECT * FROM `device_mgmt` WHERE username='$sesh'";
//echo "<br>";
//echo $device_query;
$device_result = mysqli_query($connection, $device_query) or die(mysqli_error($connection));
$row = mysqli_fetch_array($device_result,MYSQLI_ASSOC);
$devID = $row['deviceID'];
//echo $row['deviceID'];



    // If the values are posted, insert them into the database.
    if (isset($_POST['password'])){
        //$username = $_POST['username'];
	$username = $_POST['password1'];
	$email = $_POST['email'];
        $temp = $_POST['password'];

	
        $query = "update `device_mgmt` set desired_temp = $username where deviceID = $temp ";
//$query1 = "delete from `device_mgmt` where deviceID = '$password1'";
	//echo $query;
	//echo $query1;
        $result = mysqli_query($connection, $query);
        if($result){
            $smsg = "updated correctly.";

	

	//echo $query;

        }else{
		echo "<center>that device has not been registered or it does not belong to you. Please register it before trying to update it's respective temperature setting </center>";
            $fmsg ="User Registration Failed";
		//echo $query;
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<style type="text/css">
   body { background:#b3ffb3 !important; } /* Adding !important forces the browser to overwrite the default style applied by Bootstrap */
</style>

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
                    <img src="http://placehold.it/150x50&text=Logo" alt="">
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
<br><br><br>

 
<div class="container">
<?php 
$username = $_SESSION['username'];
$device_query = "SELECT username FROM `device_mgmt` WHERE username='$sesh'";
//echo "<br>";
//echo $device_query;
$device_result = mysqli_query($connection, $device_query) or die(mysqli_error($connection));
$row = mysqli_fetch_array($device_result,MYSQLI_ASSOC);
$username_email = $row['username'];
$email_query = "SELECT email FROM `user` WHERE username='$username_email'";
$email_result = mysqli_query($connection, $email_query) or die(mysqli_error($connection));
$email_row = mysqli_fetch_array($email_result,MYSQLI_ASSOC);
//echo "<br>";
//echo "<b>Your Device ID</b>";
//echo "<br>";
$devID = $row['deviceID'];

echo $devID;
$devtemp = $row['desired_temp'];
echo $devtemp;

 

?>



      <form class="form-signin" method="POST">
      
      <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php header('Location: home.php');} ?>
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <h2 class="form-signin-heading">Update Device</h2>
	<p>Please enter your new desired temperature for device <b></p>


<select class="form-control" name='password' onChange='showSelected(this.value)'>

<?php
    $conn = new mysqli('localhost', 'root', 'dataworks', 'login') 
or die ('Cannot connect to db');

    $result = $conn->query("select * from device_mgmt where username = '$sesh'");
    while ($row = $result->fetch_assoc()) {

                  unset($id, $name);
                  $id = $row[username];
                  $name = $row[deviceId]; 
			//echo $sesh;
		echo '<option>'.$name.'</option>';
                 
}
   
    ?>



</select>


</b> </p>
<script>
    /*function showSelected(val){
        console.log(val);
        //select desired_temp from device_mgmt where deviceID = 4454;
        
    }*/

	function showSelected(val){
        <?php
            $conn = new mysqli('localhost', 'root', 'dataworks', 'login') 
or die ('Cannot connect to db');

    $result = $conn->query("select desired_temp from device_mgmt where deviceID = 4454");
        $row = $result->fetch_assoc();
        echo $row[desired_temp];
        ?>
		document.getElementById
('selectedResult').innerHTML = "The selected number is: " 
+ val;
	}


    </script>
<div id='selectedResult'></div>

	<p>Currently the temperature is set at <b><?php echo $devtemp; ?></b> </p>
                <label for="inputPassword" class="sr-only">Password</label>




        <input type="text" name="password1" id="inputPassword" class="form-control" placeholder="Temp" required>
        <div class="checkbox">

        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Update</button>
        <a class="btn btn-lg btn-primary btn-block" href="login.php">Return to your homepage</a>
      </form>
</div>
 
</body>

 
</html>