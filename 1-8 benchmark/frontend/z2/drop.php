
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





<p><a href = "home.php">home</a></p>





 
</body>
 
</html>


<?php
$con=mysqli_connect("localhost","root","dataworks","login");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

// Perform queries 

echo $name;
$query = mysqli_query($con,"SELECT DateTime as date, temperature as close FROM device_msg_loggerv3 order by date desc limit 115");

//$query = mysqli_query($con,"SELECT DateTime as date, IndoorTemp as close FROM hydro_info_8 order by date desc limit 115");


    if ( ! $query ) {
        echo mysql_error();
        die;
    }
    
    $data = array();
    
    for ($x = 0; $x < mysqli_num_rows($query); $x++) {
        $data[] = mysqli_fetch_assoc($query);
    }
    
    echo json_encode($data); 


mysqli_close($con);
?>