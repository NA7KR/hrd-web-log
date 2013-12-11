<?php

if ($argc != 2 || in_array($argv[1], array('--help', '-help', '-h', '-?'))) {
?>

This is a command line PHP script with one option.

  Usage:
  <?php echo $argv[0]; ?> <option>

  <option> Enter the QSL number. 
  With the --help, -help, -h,
  or -? options, you can get this help.

<?php
} else 
{
	include "/var/www/config.php";
	$Key = $argv[1];
	$link = mysql_connect($dbhost, $dbuname, $dbpass) or die ('Cannot connect to the database: ' . mysql_error());
	mysql_select_db($dbnameHRD) or die ('Cannot connect to the database: ' . mysql_error());
	$query =  "SELECT year(COL_TIME_OFF)as 'Year', month(COL_TIME_OFF)as 'Month', day(COL_TIME_OFF)as 'Day', hour(COL_TIME_OFF)as 'Hour', minute(COL_TIME_OFF) as 'Minute',COL_CALL as 'Call', COL_BAND as 'Band', COL_MODE as 'Mode' FROM $tbHRD  WHERE `COL_PRIMARY_KEY`   = $Key";

	$result = mysql_query($query) or die ('Cannot connect to the database: ' . mysql_error());

	 //return error message if query could not be made
	if (!$result) 
	{
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
	$fileMutiply = 1000;
	$FileNoGroup = (($Key/$fileMutiply) % $fileMutiply * $fileMutiply);
    $fileNoGroupHigh = $FileNoGroup + ($fileMutiply-1);
    $filePath="/srv/cards/". $FileNoGroup ."-".$fileNoGroupHigh;
   
	if (!file_exists('$filePath')) 
		{
			mkdir($filePath, 0777, true);
		}
		
	$FileName= "$filePath/E-$Key-$Call.jpg";
	if (file_exists($FileName)) {
		echo "The file $FileName exists \n";
	} else {
		echo "The file $FileName does not exist \n";

		$str = file_get_contents("http://www.eqsl.cc/qslcard/GeteQSL.cfm?UserName=$myCall&Password=$EQSL&CallsignFrom=$Call&QSOBand=$Band&QSOMode=$Mode&QSOYear=$Year&QSOMonth=$Month&QSODay=$Day&QSOHour=$Hour&QSOMinute=$Minute");
		$start1 = '<img src=';
		$end1 = ' alt="" />';
		$pic =  getTexts($str, $start1, $end1);
		file_put_contents($FileName, file_get_contents("http://www.eqsl.cc/$pic"));
		chmod("$FileName", 0644);
		$FileName= "E-$Key-$Call.jpg";
		
		// open the directory
		$pathToThumbs = $filePath ."/thumbs/";
		$dir = opendir( $pathToThumbs );

		// load image and get image size
		$img = imagecreatefromjpeg( "{$filePath}/{$FileName}" );
		$width = imagesx( $img );
		$height = imagesy( $img );

		// calculate thumbnail size
		$thumbWidth = 100;
		$new_height = floor( $height * ( $thumbWidth / $width ) );

		// create a new temporary image
		$tmp_img = imagecreatetruecolor( $thumbWidth, $new_height );

		// copy and resize old image into new image
		imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $thumbWidth, $new_height, $width, $height );

		// save thumbnail into a file
		imagejpeg( $tmp_img, "{$pathToThumbs}{$FileName}" );
		chmod("{$pathToThumbs}{$FileName}", 0644);
		// close the directory
		closedir( $dir );
	}
}
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
   }
   return $text;
}
?> 
