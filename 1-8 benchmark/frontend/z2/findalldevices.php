

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome - <?php echo $userRow['userEmail']; ?></title>
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<h1>hi</h1>

<body>



<?php
$mysqli = new mysqli("localhost", "root", "dataworks", "login");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query = "SELECT username, deviceID FROM device_mgmt";

if ($result = $mysqli->query($query)) {

    /* fetch object array */
    while ($row = $result->fetch_row()) {
?>



<div class="container">
	<table class="table">
	<thead>
	      <tr>
		<th>UserName</th>
		<th>DeviceID</th>

	<tbody>

	<tr class="info">
	<!--<td><?php printf ("%s %s\n", $row[0], $row[1]); ?></td>-->
	<td><?php echo $row[0]; ?></td>
	<td><?php echo $row[1]; ?></td>
	
	</tr>
	</tody>

</tbody>
</table>
</div>

        <?php
    }

    /* free result set */
    $result->close();
}

/* close connection */
$mysqli->close();
?>