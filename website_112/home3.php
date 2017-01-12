
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
                <a class="navbar-brand" href="home.php">
                    <img src="vs_text_logo.png" width="300" height="40" alt="">
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="nav-link active">
                        <a href="home.php">Home</a>
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


<br>
<br>
<br>
<div align = "center">
	<h1> Welcome <b><?php echo $username_email?> </b></h1>

</div>




<br>

    <body>
	<div class="container">
  <h2>Device Info</h2>

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
		<center>Warning Temperature 
		</b>
			<a href="device_update2.php" data-toggle="popover" data-trigger="hover" data-content="Change your Devices Temperature" class="glyphicon glyphicon-fire"></a>
		</center></td>


</td>
               		<td><b><center>Last Recored Temperature </b></center></td>

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


if($minutes_str > 1440){
	$minutes_str = "Over a day ago";
}


}


?>


	
	<tr class="info">


	<!--<td><?php printf ("%s %s\n", $row[0], $row[1]); ?></td>-->
	<td><b><center><?php echo $row[0]; ?> </center></td>
	<td><b><center><?php echo $row[3]; ?> F </center></td>
	<td><b><center><?php echo $row[5]; ?> F <a onclick = "showGraph('<?php echo $row[0]; ?>', '<?php echo $_SESSION['username'];?>')" data-toggle="popover" data-trigger="hover" data-content="View the past 24 hours of device <?php echo $row[0];?>'s activity" class="glyphicon glyphicon-signal"></a></center></td>
	<td><b><center><?php echo $minutes_str; ?> </center></td>
	<!--<td><b><center><a href = "zgraphs.html">graphs</a></center></td>-->
	<!--<td><b><center><a onclick = "showGraph('<?php echo $row[0]; ?>', '<?php echo $_SESSION['username'];?>')">graph</a></center></td>-->
	
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
              <a href="registerdevice.php" class="btn btn-info">
          <span class="glyphicon glyphicon-plus"></span> 

	<a href="deletedevice_drop.php" class="btn btn-info btn btn-danger">
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
  stroke: green;
  stroke-width: 2.1px;
}


path.area {
  fill: #ede1e1;
}

.axis {
  shape-rendering: crispEdges;
}

.x.axis line,
.x.axis path {
  fill: #e7e7e7;
  stroke: #111;
}


.y.axis line,
.y.axis path {
  fill: #e7e7e7;
  stroke: #111;
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
<div id="myModal" class="modal" role="dialog">
<!-- Modal content -->

  <div id = "modal-content" class="modal-content" data-toggle="modal">
	
    <span class="close" >&times;</span>

    <!--<p>Chart</p>-->
    <p id = "null"></p>

  </div>
</div>


<script>
var modal = document.getElementById('myModal');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
    
}
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        console.log("modal " + modal.width);
    }
}

        var margin = {
                top: 30,
                right: 100,
                bottom: 30,
                left: 50
            },
            width = 1000 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;
height = $(window).height()*.5;   // returns height of browser viewport
height =$(document).height()*.5; // returns height of HTML document
width = $(window).width()*.7;   // returns width of browser viewport
width = $(document).width()*.5;

const ticks = 6

        var svg = d3.select("#modal-content").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        var parseDate = d3.time.format("%Y-%m-%d %H:%M:%S").parse;

        var x = d3.time.scale()
            .range([0, width]);

        var y = d3.scale.linear()
            .range([height, 0]);


        var xAxis = d3.svg.axis()
            .scale(x)
            .orient("bottom").ticks(8);

        var yAxis = d3.svg.axis()
            .scale(y)
            .orient("left").ticks(8);

        var line = d3.svg.line()
            .x(function (d) {
                return x(d.datetime);
            })
            .y(function (d) {
                return y(d.temperature);
            });

        var loadData = function (data) {
            width = $(document).width()*.6;
            svg.attr("width", width);
             svg.selectAll("g").remove();
            svg.selectAll("text").remove();
            svg.selectAll("path").remove();
            x.domain(d3.extent(data, function (d) {
                return d.datetime;
            }));
            y.domain(d3.extent(data, function (d) {
                return d.temperature;
            }));

            axis = svg.append('g')
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis)
                .append("text")
                .attr("x", 6)
                .attr("dx", ".2em")
                .attr("transform", "translate(0," + height + ")")
                .style("text-anchor", "end")
                .text("Date");
		
            svg.append("g")
                .attr("class", "y axis")
                .call(yAxis)
                .append("text")
                .attr("transform", "rotate(-90)")
                .attr("y", 1)
                .attr("dy", ".71em")
                .style("text-anchor", "end")


            svg.append("path")
                .datum(data)
                .attr("class", "line")
                .attr("d", line);


    svg.append("text")
        .attr("transform", "rotate(-90)")
        .attr("y", 6)
        .attr("x", margin.top - (height / 2))
        .attr("dy", ".71em")
        .style("text-anchor", "end")
        .text("Temp(F)");


    svg.append("text")
        .attr("x", (width / 2))     
        .attr("y", 0 - (margin.top / 2))
        .attr("text-anchor", "middle")
        .style("font-size", "16px")
        .style("text-decoration", "underline")
        .text("Temperature Line Graph");



        }
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});

function showGraph(devid, usrnm){
    modal.style.display = "block";
    console.log("modal " + modal.offsetWidth);
    console.log("blah " + devid + " " + usrnm);
    //select  ts, temperature from device_msg_loggerv3 where deviceID = devid order by ts desc limit 115
     $.ajax({
            type: 'GET',
            url: 'graphquery.php?devid=' + devid,
            success: function (data) {
                //alert(data);

                /*jsonData = JSON.parse(json.stringify(data));
                //console.log(jsonData);*/
                for(var i = 0; i < data.length; i++){
                    data[i].datetime = new Date(data[i].datetime);
                    data[i].temperature = parseFloat(data[i].temperature);
                }
                console.log(data[0]);
                if(data.length != 0)
                    loadData(data);
                else
                    document.getElementById('modal-content').getElementsByTagName('p')[1].innerHTML = "no data";
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


    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

<p><b><h4>Dont have one? Buy one here!</h4></p>
<br><br><br><br>
 <h5>MeanGreens LLC 2017</h5>
</html>