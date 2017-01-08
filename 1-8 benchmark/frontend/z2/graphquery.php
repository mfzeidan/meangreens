<?php
function get_Date($devid) {
$con=mysqli_connect("localhost", "root", "dataworks", "login");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

$query = mysqli_query($con,"select  ts, temperature from device_msg_loggerv3 where deviceID = '$devid' order by ts desc limit 50");
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
}
header("Content-Type: application/json", true);
echo get_Date($_GET['devid']);
?>
