<?php
		$dbhost="localhost" ; 	//server 
		$dbuname="root";		//DB user
		$dbpass="password"; 	// Database Password
		$dbnameHRD="call";		// Callsign or Database name if diffrent
		$EQSL="password"; 	//EQSL logon
		$dbnameWEB="HRD_Web";	//database name
		$tbStates="tb_States_Countries";//web Table name
		$tbHRD="TABLE_HRD_CONTACTS_V01";//HRD Table name
		$tbSelect="tb_Select";
		$myCall="call";
		$CountrysArray = [
							"USA" => "USA", 	  //to display on page add // to remove
							"GB" => "GB", 		  //to display on page add // to remove
							"CA" => "CA", //to display on page add // to remove
						];
		$debug="false";
		
?>
<style type="text/css">
	.auto-style1 
	{
		text-align: center;
		font-family: Arial, Helvetica, sans-serif;
		font-size: large;
	}
	.auto-style2 {color: #FFFFFF;}
	.auto-style3 {font-size: xx-small;}
	.auto-style4 {font-size: larger;}
	.auto-style5 {font-size: medium;}
	.sucess {color:#088A08;}
	.error{color:red;}
	.background1 
	{
		background-color: #0000FF;
		color: #FFFFFF;
	}
	a:link {color:#FF0000;}    /* unvisited link */
	a:visited {color:#00FF00;} /* visited link */
	a:hover {color:#FF00FF;}   /* mouse over link */
	a:active {color:#0000FF;}  /* selected link */
</style>

