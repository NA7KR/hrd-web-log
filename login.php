<?php
include_once("config.php");
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
	<meta http-equiv="Content-Type" content="text/html; charset=us-ascii">
	<meta name="description" content="<?php echo $myCall ?> Ham Radio LogBook"> 
	<meta http-equiv="content-type" content="text/html;charset=UTF-8"> 
	<meta name="revisit-after" content="30 days">
	<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
</head>

<body span class="background1">
<body bgcolor="#FFFFFF">
<div align="center">
<div style="width:300px; border: solid 1px #333333; " align="left">
<div style="background-color:#333333; "> <div class="auto-style1"> <b>Login</b></div>
<div style="margin:30px">
<form action="" method="post">
<label><span class="auto-style5">UserName  :</label>
<input type="text" name="username" class="box"><br><br>
<label><span class="auto-style5">Password  :</label>
<input type="password" name="password" class="box" ><br><br>
<div align="center"><input type="submit" value=" Submit "/><br>
</form>

<?php echo "<div class='error'>" . $error; ?></div>
</div>
</div>
</div>
</body>
</html>
