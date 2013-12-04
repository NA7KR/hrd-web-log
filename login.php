<!DOCTYPE HTML 4.01 Transitional>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="<?php echo $myCall ?> Ham Radio LogBook">
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="revisit-after" content="30 days">
	<meta name="ROBOTS" content="INDEX, FOLLOW">
<?php
include_once("config.php");
include_once("style.php");
$bd = mysql_connect($dbhost, $dbuname, $dbpass) or die("Opps some thing went wrong");
mysql_select_db($dbnameWEB, $bd) or die("Opps some thing went wrong");
session_start();
include "counter.php";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$myusername = mysql_real_escape_string($_POST['username']); // username and password sent from form 
	$mypassword = mysql_real_escape_string($_POST['password']);
	$sql="SELECT id FROM admin WHERE username='$myusername' and passcode='$mypassword'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$active=$row['active'];
	$count=mysql_num_rows($result);

	if($count==1) // If result matched $myusername and $mypassword, table row must be 1 row
	{
		//session_register("myusername");
		$_SESSION['login_user']=$myusername;

		header("location: welcome.php");
	}
	else 
	{
		$error="Your Login Name or Password is invalid";
	}
}

?>	
<title><?php echo $myCall ?> Ham Radio LogBook Admin</title>	

</head>
	<body class="background1">
	<div class="c3 c1 c1">
	<div class="auto-style1"><strong>Login.</strong></div>
	<div class="c2">
	<form action="login.php" method="post"><label>UserName :</label> <input type="text" name="username" class="box"><br>
	<br>
	<label>Password :</label> <input type="password" name="password" class="box"><br>
	<br>
	<div class="c1"><input type="submit" value=" Submit "><br></div>
	<div class='error'></div>
	</form>
	</div>
	</div>
	<div class="c1"><span class="auto-style5"><a href="http://jigsaw.w3.org/css-validator/check/referer"><img class="c4" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!"></a></span></div>
	</body>
</html>
