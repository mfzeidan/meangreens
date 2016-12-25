<?php
$con=mysqli_connect("localhost","root","dataworks","ddhydro");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

// Perform queries 


$query = mysqli_query($con,"SELECT DateTime as date, OutdoorTemp as close FROM hydro_info_8 order by date desc limit 115");

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