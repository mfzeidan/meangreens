
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
 
        $query = "delete from `device_mgmt` where deviceID = '$password'";
        $result = mysqli_query($connection, $query);
        if($result){
            $smsg = "Device Deletion Successful.";
        }else{
            $fmsg ="Device Deletion Unsuccessful";
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

<script type="text/javascript">
	function showSelected(val){
		document.getElementById
('selectedResult').innerHTML = "The selected device is - " 
+ val;
	}
</script>




 
<div class="container">
      <form class="form-signin" method="POST">
      
      <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <h2 class="form-signin-heading">Delete your Device</h2>
	<p>We're sorry to see you go :(</p>


<select name='password' onChange='showSelected(this.value)'>
	

<?php
    $conn = new mysqli('localhost', 'root', 'dataworks', 'login') 
or die ('Cannot connect to db');

    $result = $conn->query("select * from device_mgmt where username = '$sesh'");
    while ($row = $result->fetch_assoc()) {

                  unset($id, $name);
                  $id = $row[username];
                  $name = $row['deviceId']; 
			//echo $sesh;
                  echo '<option value="'.$name.'">'.$name.'</option>';
                 
}
   
    ?>
</select>

<div id='selectedResult'></div>

	  <!--<span class="input-group-addon" id="basic-addon1">@</span>-->
	  <!--<input type="text" name="username" class="form-control" placeholder="Username" required>-->
<br>
        <!--<label for="inputEmail" class="sr-only">Email address</label>
        <!-<input type="text" name="email" id="inputEmail" class="form-control" placeholder="DeviceID" required autofocus>-->
        <!--<label for="inputPassword" class="sr-only">Password</label>-->
        <!--<input type="text" name="password" id="inputPassword" class="form-control" placeholder="Temperature" required>-->
        <div class="checkbox">

        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Delete Device</button>
        <a class="btn btn-lg btn-primary btn-block" href="login.php">Return to your homepage</a>
      </form>
</div>








 
</body>
 
</html>