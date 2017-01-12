
<?php
	require('connect.php');
    // If the values are posted, insert them into the database.
    if (isset($_POST['username']) && isset($_POST['password'])){
        $username = mysqli_real_escape_string($connection, $_POST['username']);
	$email = mysqli_real_escape_string($connection,$_POST['email']);
        $password = md5($_POST['password']);
	$confirm_pw = md5($_POST['confirm-password']);

	if($_POST['password'] != $_POST['confirm-password']){
		echo "Your passwords did not match";
		echo " <a href='register.php'> Please Try Again :)</a>"; 
		exit();
	}
 
        $query = "INSERT INTO `user` (username, password, email, active) VALUES ('$username', '$password', '$email',1)";
        $result = mysqli_query($connection, $query);
        if($result){
            $smsg = "User Created Successfully.";
		header('Location: http://159.203.165.37/dblogin/z2/login.php');
		//exit();
        }else{
            $fmsg ="User Registration Failed. This username already exists!";
	//printf("Errormessage: %s\n", $mysqli->errno);
        }
    }
    ?>
<html>
<head>
	<title>VoltSync User Registration</title>
	
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
 
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
 
<link rel="stylesheet" href="styles.css" >
 
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<style>
  body { background:#b3ffb3 !important; }
</style>


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
                <a class="navbar-brand" href="#">
                    <img src="vs_text_logo.png" width="300" height="40" alt="">
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#">Home</a>
                    </li>
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="product_page.html#">Buy</a>
                    </li>

                    <li class="navbar-right">
                        <a href='logout.php'>Logout</a>
                    </li>

                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
<br><br><br>

<style>

.btn-primary {

      background-color: #5cb85c !important;
      color:white !important;
      text-align: center;

}

</style>

<div class="container">
      <form class="form-signin" method="POST">
      
      <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?>
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <h2 class="form-signin-heading">Please create an account below</h2><br>
        <div class="input-group">
	  <span class="input-group-addon" id="basic-addon1">@</span>
	  <input type="text" name="username" class="form-control" placeholder="Username" required>
	</div>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
		<br>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required pattern=".{6,13}" required title="6 to 13 characters">
        <input type="password" name="confirm-password" id="inputPassword" class="form-control" placeholder="Confirm Password" required pattern=".{6,13}" required title="6 to 13 characters">

        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-success btn-block" type="submit">Register</button>
<br>
        <a class="btn btn-lg btn-success btn-block" href="login.php">Return Home</a>
      </form>
</div>
 
</body>
 
</html>