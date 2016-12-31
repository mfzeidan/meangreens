
<?php
function get_dev($email, $devid) {
$con=mysqli_connect("localhost","root","dataworks","wetness_monitor");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$query ="INSERT INTO emailDeviceID (email, deviceID)
VALUES ('$email', '$devid')";
    
    if ($con->query($query) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


mysqli_close($con);
}
header("Content-Type: application/json", true);
echo get_dev($_GET['email'], $_GET['devid']);
?>
