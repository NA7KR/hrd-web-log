<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta name="keywords" content="Ham Radio NA7KR">
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
if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) { // if request is not secure, redirect to secure url
    $url = 'https://' . $_SERVER['HTTP_HOST']
                      . $_SERVER['REQUEST_URI'];

    header('Location: ' . $url);
}
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
	<div class="center">
	<form action="login.php" method="post">
		<table width="100%" border="1" cellpadding="2" cellspacing="2" bgcolor="#333333" >
			<tr valign="top">
				<td colspan=2 style="border-width : 0px;"><div class="auto-style1"><strong>Login.</strong></div><br></td>
			</tr>
			<tr valign="top">
				<td style="border-width : 0px;"><label>UserName :</label></td>
				<td style="border-width : 0px;"><input type="text" name="username" class="box"></td>
			</tr>
			<tr valign="top">
				<td style="border-width : 0px;"><label>Password :</label></td>
				<td style="border-width : 0px;"><input type="password" name="password" class="box"></td>
			</tr>
			<tr valign="top">
				<td colspan=2 style="border-width : 0px;"><div class="c1"><input type="submit" value=" Submit "></div><br></td>
			</tr>
			<tr valign="top">
			<td colspan=2 style="border-width : 0px;"><div class='error'><?php echo $error; ?></div><br></td></tr>
		</table>
	</form>
	</div>
	<br>
	<br>
	<div class="c1"><span class="auto-style5"><a href="http://jigsaw.w3.org/css-validator/check/referer"><img class="c4" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!"></a></span></div><br><br>
	<div class="c1"><span class="auto-style5"><a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88"></a></span></div>
	</body>
</html>
