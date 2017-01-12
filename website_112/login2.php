
<?php
require_once 'connect.php';
if (isset($_POST['submit'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
 
$sql = "SELECT * FROM `confirm` WHERE username='$username' and password='$password' and active=1";
$result = mysql_query($sql) or die(mysql_error());
$count = mysql_num_rows($result);
if ($count == 1){
	echo "You are logged in";
}else {
	echo "Login Failed";
}
}
?>
<html>
<head>
<title>Login</title>
</head>
<body>
<h1></h1>
<form action="" method="post">
<label>User Name :</label>
<input type="text" name="username" /><br />
<label>Password</label>
<input type="password" name="password" /><br />
<input type="submit" value="Login" name="submit"/>
</form>
<?php
 
?>
</body>
</html>