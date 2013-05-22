<?php
include "config.php";
$Key = 2;
$link = mysql_connect($dbhost, $dbuname, $dbpass) or die ('Cannot connect to the database: ' . mysql_error());
mysql_select_db($dbnameHRD) or die ('Cannot connect to the database: ' . mysql_error());
$query =  "SELECT year(COL_TIME_OFF)as 'Year', month(COL_TIME_OFF)as 'Month', day(COL_TIME_OFF)as 'Day', hour(COL_TIME_OFF)as 'Hour', minute(COL_TIME_OFF) as 'Minute',COL_CALL as 'Call', COL_BAND as 'Band', COL_MODE as 'Mode' FROM $tbHRD  WHERE `COL_PRIMARY_KEY`  = $Key";

$result = mysql_query($query) or die ('Cannot connect to the database: ' . mysql_error());

 //return error message if query could not be made
if (!$result) {
	echo "Could not successfully run query ($query) from DB: " . mysql_error();
	exit;
}

while ($value = mysql_fetch_array($result))
{
	$Year = $value['Year'];
	$Month = $value['Month'];
	$Day = $value['Day'];
	$Hour = $value['Hour'];
	$Minute = $value['Minute'];
	$Band = $value['Band']; 
	$Mode = $value['Mode']; 
	$Call = $value['Call'];
	
}
mysql_close($link);

$str = file_get_contents("http://www.eqsl.cc/qslcard/GeteQSL.cfm?UserName=$myCall&Password=$dbpass&CallsignFrom=$Call&QSOBand=$Band&QSOMode=$Mode&QSOYear=$Year&QSOMonth=$Month&QSODay=$Day&QSOHour=$Hour&QSOMinute=$Minute");
$start1 = '<img src=';
$end1 = ' alt="" />';
$pic =  getTexts($str, $start1, $end1);

function getTexts($string, $start, $end)
{
   $text = "";
   $posStart = strrpos($string, $start);
   $posEnd = strrpos($string, $end, $posStart);
   if($posStart > 0 && $posEnd > 0)
   {
	$posStart =$posStart + strlen($start) + 2;
	$posEnd--;
	$text = substr($string, $posStart,  $posEnd-$posStart);
       //$text = substr($string, $posStart, strlen($string) - $posEnd);
   }
   return $text;
}
$FileName= "E-$Key-$Call.jpg";
file_put_contents($FileName, file_get_contents("http://www.eqsl.cc/$pic"));
?>
