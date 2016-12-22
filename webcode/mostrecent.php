<?php
$con=mysqli_connect("localhost","root","dataworks","ddhydro");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

// Perform queries 


$query = mysqli_query($con,"SELECT MAX(DateTime) as date FROM hydro_info_8");

    if ( ! $query ) {
        echo mysql_error();
        die;
    }
    
    $data = array();
    
    for ($x = 0; $x < mysqli_num_rows($query); $x++) {
        $data[] = mysqli_fetch_assoc($query);
    }
    
    $json = json_encode($data);
 	$val = substr($json,10,19);	
	//$yummy = json_decode($json, true);
	//echo $data[0];


mysqli_close($con);
?>