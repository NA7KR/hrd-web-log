<?php
include('config.php');
$bd = mysql_connect($dbhost, $dbuname, $dbpass) or die("Opps some thing went wrong");
mysql_select_db($dbnameWEB, $bd) or die("Opps some thing went wrong");
session_start();
$user_check=$_SESSION['login_user'];

	
$ses_sql=mysql_query("select username from admin where username='$user_check' ");

$row=mysql_fetch_array($ses_sql);

$login_session=$row['username'];

if(!isset($login_session))
{
header("Location: login.php");
}
?>