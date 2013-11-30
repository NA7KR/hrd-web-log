<?php

include("config.php");
$bd = mysql_connect($dbhost, $dbuname, $dbpass) or die("Opps some thing went wrong");
mysql_select_db($dbnameWEB, $bd) or die("Opps some thing went wrong");
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST")
{
// username and password sent from form 

	//$myusername=addslashes($_POST['username']); 
	//$mypassword=addslashes($_POST['password']); 
	
	$myusername = mysql_real_escape_string($_POST['username']);
	$mypassword = mysql_real_escape_string($_POST['password']);
	
	
	$sql="SELECT id FROM admin WHERE username='$myusername' and passcode='$mypassword'";
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result);
	$active=$row['active'];

	$count=mysql_num_rows($result);


	// If result matched $myusername and $mypassword, table row must be 1 row
	if($count==1)
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


<style type="text/css">
	.auto-style1 
	{
		text-align: center;
		font-family: Arial, Helvetica, sans-serif;
		font-size: large;
	}
	.auto-style2 
	{
		color: #FFFFFF;
	}
	.auto-style3 
	{
		font-size: xx-small;
	}
	.auto-style4 
	{
		font-size: larger;
	}
	body
</style>
	<?php
		include "config.php";
		include "counter.php";
	?>
	<title><?php echo $myCall ?> Ham Radio LogBook</title> 
	<meta http-equiv="Content-Type" content="text/html; charset=us-ascii">
	<meta name="description" content="<?php echo $myCall ?> Ham Radio LogBook"> 
	<meta http-equiv="content-type" content="text/html;charset=UTF-8"> 
	<meta name="revisit-after" content="1 days">
	<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
</head>

<body  style="color: #FFFFFF; background-color: #0000FF">


<body bgcolor="#FFFFFF">


<div align="center">
<div style="width:300px; border: solid 1px #333333; " align="left">
<div style="background-color:#333333; color:#FFFF00; padding:3px;"><b>Login</b></div>


<div style="margin:30px">

<form action="" method="post">
<label><font color="yellow">UserName  :</label><input type="text" name="username" class="box"/><br /><br />
<label>Password  :</label><input type="password" name="password" class="box" /><br/><br />
<input type="submit" value=" Submit "/><br />

</form>
<div style="font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
</div>
</div>
</div>

</body>
</html>
