
<?php
 session_start();
 require_once 'connect.php';
 if( !isset($_SESSION['username']) ) {
  header("Location: login.php");
  exit;
 }

date_default_timezone_set('America/New_York');



$username = $_SESSION['username'];

$device_query = "SELECT * FROM `device_mgmt` WHERE username='$username'";

$device_result = mysqli_query($connection, $device_query) or die(mysqli_error($connection));

$row = mysqli_fetch_array($device_result,MYSQLI_ASSOC);

$username_email = $row['username'];


$email_query = "SELECT email FROM `user` WHERE username='$username'";

$email_result = mysqli_query($connection, $email_query) or die(mysqli_error($connection));

$email_row = mysqli_fetch_array($email_result,MYSQLI_ASSOC);

$ID = $row['deviceID'];

?>

<html>
<style type="text/css">
   body { background:#b3ffb3 !important; } /* Adding !important forces the browser to overwrite the default style applied by Bootstrap */
</style>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome - <?php echo $userRow['userEmail']; ?></title>
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <script src="http://d3js.org/d3.v3.min.js"></script>
</head>

<div align = "center">
	<h1> Welcome <?php echo $username_email?> </h1>

</div>




<br>

    <body>
	<div class="container">
  <h2>Your Device Info</h2>

	<div class="pull-left">
	<h4><b><?php echo $email_row['email']?> </b>
				
	<a href="email_update.php" data-toggle="popover" data-trigger="hover" data-content="Click to Change your Email Here" class="glyphicon glyphicon-pencil"><right></right></a></span>
		</span></a>

	</h4>
        </div>
<br>
<br><br>
        <table class= "table table-striped">
        <thead>
	
            <tr>
		
                <td><b><center>DeviceID</center></td>
               	<td><b>
		<center>Desired Temperature 
		</b>
			<a href="device_update2.php" data-toggle="popover" data-trigger="hover" data-content="Change your Devices Temperature" class="glyphicon glyphicon-fire"></a>
		</center></td>


</td>
               		<td><b><center>Last Recored Temperature </b><a href="device_update2.php" data-toggle="popover" data-trigger="hover" data-content="View the past 24 hours of the device's activity" class="glyphicon glyphicon-signal"></a></span></center></td>

                <td><b><center>Last Message Received</center></td>

            </tr>
        </thead>
        <tbody>




<?php
$mysqli = new mysqli("localhost", "root", "dataworks", "login");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//$query = "select dev.deviceID,usr.username, usr.email, dev.desired_temp,b.ts, b.temp from user usr inner join device_mgmt dev on usr.username = dev.username left outer join (select distinct(deviceID), max(ts) ts, max(temperature) temp from device_msg_loggerv2 group by deviceID) b on b.deviceID = dev.deviceID where usr.username = '$username'";
//echo $query;

$query = "
select dev.deviceID,usr.username, usr.email, dev.desired_temp,ifnull(c.ts,0) ts,ifnull(d.temperature,0) temperature
from user usr
left outer join device_mgmt dev
on usr.username = dev.username
left outer join (
select distinct(deviceID) deviceID
from device_mgmt
group by deviceID
) b
on b.deviceID = dev.deviceID
left outer join(
select max(ts) ts, deviceID from device_msg_loggerv3
group by deviceID
) c
on c.deviceID = b.deviceID
left outer join(
select COALESCE(temperature,0) temperature,ifnull(ts,946684861) ts from device_msg_loggerv3
)d
on c.ts = d.ts
where usr.username = '$username'
";

//echo $query;


if ($result = $mysqli->query($query)) {

    /* fetch object array */
    while ($row = $result->fetch_row()) {


$datetimeFormat = 'm-d-Y H:i:s';
$date = new \DateTime();


if($row[4] == 0)

{
	$minutes_str = "no messages yet";
}
else{
$date->setTimestamp($row[4]);



$last_ts = $date->format($datetimeFormat);

$date2 = date('m-d-Y H:i:s', time());
//date is the current time, $last_ts is old time




$start_date = new DateTime(date('m-d-Y H:i:s', time()));

$since_start = $last_ts;


//echo $last_ts;

$start_date = new DateTime(date('m-d-Y H:i:s', time()));


$since_start =$start_date->diff(new DateTime($last_ts));

$minutes = $since_start->days * 24 * 60;
$minutes += $since_start->h * 60;
$minutes += $since_start->i;
$minutes_str = $minutes . " minutes ago";

}


?>


	
	<tr class="info">


	<!--<td><?php printf ("%s %s\n", $row[0], $row[1]); ?></td>-->
	<td><b><center><?php echo $row[0]; ?> </center></td>
	<td><b><center><?php echo $row[3]; ?> F </center></td>
	<td><b><center><?php echo $row[5]; ?> F </center></td>
	<td><b><center><?php echo $minutes_str; ?> </center></td>
	<td><b><center><a onclick = "showGraph('<?php echo $row[0]; ?>', '<?php echo $_SESSION['username'];?>')">graph</a></center></td>

	
	</tr>


        <?php
    }

    /* free result set */
    $result->close();
}

/* close connection */
$mysqli->close();
?>

            </tbody>
            </table>
		<h4> <b>Add/Remove a device!</h4>
              <a href="registerdevice.php" class="btn btn-info btn-lg">
          <span class="glyphicon glyphicon-plus"></span> 

	<a href="deletedevice_drop.php" class="btn btn-info btn-lg btn-danger">
          <span class="glyphicon glyphicon-minus"></span> 



        </a>

<br>
<br>

<style>
    
.axis--x path {
  display: none;
}

.line {
  fill: none;
  stroke: steelblue;
  stroke-width: 1.5px;
}
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>

<!-- The Modal -->
<div id="myModal" class="modal">


<script>
var modal = document.getElementById('myModal');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
// When the user clicks on <span> (x), close the modal
/*span.onclick = function() {
    modal.style.display = "none";
}*/

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
        var margin = {
                top: 20,
                right: 20,
                bottom: 30,
                left: 50
            },
            width = 960 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;
        var svg = d3.select("body").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        var x = d3.time.scale()
            .range([0, width]);

        var y = d3.scale.linear()
            .range([height, 0]);


        var xAxis = d3.svg.axis()
            .scale(x)
            .orient("bottom");

        var yAxis = d3.svg.axis()
            .scale(y)
            .orient("left");

        var line = d3.svg.line()
            .x(function (d) {
                return x(d.ts);
            })
            .y(function (d) {
                return y(d.temperature);
            });

        var loadData = function (data) {
             svg.selectAll("g").remove();
            svg.selectAll("path").remove();
            x.domain(d3.extent(data, function (d) {
                return d.ts;
            }));
            y.domain(d3.extent(data, function (d) {
                return d.temperature;
            }));

            axis = svg.append('g')
                .attr("class", "x axis")
                //.attr("transform", "translate(0," + height + ")")
                .call(xAxis)
                .append("text")
                .attr("x", 6)
                .attr("dx", ".71em")
                .style("text-anchor", "end")
                .text("Date");

            svg.append("g")
                .attr("class", "y axis")
                .call(yAxis)
                .append("text")
                .attr("transform", "rotate(-90)")
                .attr("y", 6)
                .attr("dy", ".71em")
                .style("text-anchor", "end")
                .text("Temp");

            svg.append("path")
                .datum(data)
                .attr("class", "line")
                .attr("d", line);
        }
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});

function showGraph(devid, usrnm){
    modal.style.display = "block";
    console.log("blah " + devid + " " + usrnm);
    //select  ts, temperature from device_msg_loggerv3 where deviceID = devid order by ts desc limit 50
     $.ajax({
            type: 'GET',
            url: 'graphquery.php?devid=' + devid,
            success: function (data) {
                //alert(data);

                /*jsonData = JSON.parse(json.stringify(data));
                //console.log(jsonData);*/
                for(var i = 0; i < data.length; i++){
                    data[i].ts = parseFloat(data[i].ts);
                    data[i].temperature = parseFloat(data[i].temperature);
                }
                console.log(data[0]);
                loadData(data);
            },
            error: function (xhr, status, thrown) {
                console.log("jdlabla " + xhr + " " + status + " " + thrown);
            }
        });
}
</script>

          
        
</div>
    </body>


<div align="center">
<br>

<p><b><h4>Dont have one? Buy one here!</h4></p>

 <h5><a href='logout.php'><b>Logout</b></a></h5>
</html>