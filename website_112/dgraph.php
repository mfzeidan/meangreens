<?php
 session_start();
 require_once 'connect.php';
 if( !isset($_SESSION['username']) ) {
  header("Location: login.php");
  exit;
 }

?>


<!DOCTYPE html>
<meta charset="utf-8">

<center><h1>MeanGreens</h1></center>



<b><p id="insert"></p></b>

Click <a href="http://meangreensfarm.com/mqtt/lamp_home_5.html">here</a> to change your control your greenhouse temperature.

<br>
<br>
<style> /* set the CSS */

body { font: 12px Arial;}

path { 
  stroke: steelblue;
	stroke-width: 2;
	fill: none;
}

.axis path,
.axis line {
	fill: none;
	stroke: grey;
	stroke-width: 1;
	shape-rendering: crispEdges;
}

</style>

<body>

<!-- load the d3.js library -->	
<script src="http://d3js.org/d3.v3.min.js"></script>

<script>

// Set the dimensions of the canvas / graph
var	margin = {top: 30, right: 20, bottom: 30, left: 50},
	width = 400 - margin.left - margin.right,
	height = 220 - margin.top - margin.bottom;

// Parse the date / time
var parseDate = d3.time.format("%Y-%m-%d %H:%M:%S").parse;

// Set the ranges
var	x = d3.time.scale().range([0, width]);
var	y = d3.scale.linear().range([height, 0]);

// Define the axes
var	xAxis = d3.svg.axis().scale(x)
	.orient("bottom").ticks(5);

var	yAxis = d3.svg.axis().scale(y)
	.orient("left").ticks(5);

// Define the line
var	valueline = d3.svg.line()
	.x(function(d) { return x(d.date); })
	.y(function(d) { return y(d.close); });
    
// Adds the svg canvas
var	chart1 = d3.select("body")
	.append("svg")
	.text("hi")
		.attr("width", width + margin.left + margin.right)
		.attr("height", height + margin.top + margin.bottom)
	.append("g")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")").text("Value vs Date Graph");

var newdate; 
if(newdate != null){
	console.log(newdate);
}

function updateDate(date){
	document.getElementById("insert").innerHTML = date;
}

// Get the data
d3.json("dev.php", function(error, data) {
    data.forEach(function(d) {
        d.date = parseDate(d.date.toString());
	newdate = d.date;
        d.close = +d.close;
    });
	console.log(newdate);
	updateDate(newdate);
	// Scale the range of the data
	x.domain(d3.extent(data, function(d) { return d.date; }));
	y.domain([0, d3.max(data, function(d) { return d.close; })]);

	// Add the valueline path.
	chart1.append("path")
		.attr("class", "line")
		.attr("d", valueline(data));

	// Add the X Axis
	chart1.append("g")
		.attr("class", "x axis")
		.attr("transform", "translate(0," + height + ")")
		.call(xAxis);

	// Add the Y Axis
	chart1.append("g")
		.attr("class", "y axis")
		.call(yAxis);

});

	
// Get the data
d3.json("indoor_temp.php", function(error, data) {
    data.forEach(function(d) {
        d.date = parseDate(d.date.toString());
        d.close = +d.close;
    });

	// Scale the range of the data
	x.domain(d3.extent(data, function(d) { return d.date; }));
	y.domain([0, d3.max(data, function(d) { return d.close; })]);

	// Add the valueline path.



});



// Get the data
d3.json("sm.php", function(error, data) {
    data.forEach(function(d) {
        d.date = parseDate(d.date.toString());
        d.close = +d.close;
    });

	// Scale the range of the data
	x.domain(d3.extent(data, function(d) { return d.date; }));
	y.domain([0, d3.max(data, function(d) { return d.close; })]);

    chart1.append("text")
        .attr("transform", "rotate(-90)")
        .attr("y", 6)
        .attr("x", margin.top - (height / 2))
        .attr("dy", ".71em")
        .style("text-anchor", "end")
        .text("Temp (F)");


    chart1.append("text")
        .attr("x", (width / 2))     
        .attr("y", 0 - (margin.top / 2))
        .attr("text-anchor", "middle")
        .style("font-size", "16px")
        .style("text-decoration", "underline")
        .text("Temperature Outside Greenhouse");





});






</script>
</body>