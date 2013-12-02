<?php
include("config.php");
$bd = mysql_connect($dbhost, $dbuname, $dbpass) or die("Opps some thing went wrong");
mysql_select_db($dbnameWEB, $bd) or die("Opps some thing went wrong");
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST")
{
// username and password sent from form 
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
<div style="background-color:#333333; "> <div class="auto-style1"> <b>Login</b></div>
<div style="margin:30px">
<form action="" method="post">
<label></span><span class="auto-style4">UserName  :</label><input type="text" name="username" class="box"/><br /><br />
<label>Password  :</label><input type="password" name="password" class="box" /><br/><br />
<div align="center"><input type="submit" value=" Submit "/><br />
</form>
<div style="font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
</div>
</div>
</div>
</body>
</html>
