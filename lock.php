<?php
/***************************************************************************
*			NA7KR Log Program 
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
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