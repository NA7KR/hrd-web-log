<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">
	function makeEnable1()
	{	
		var x=document.getElementById("mySelect1")
		x.disabled=false
		var x=document.getElementById("mySelect2")
		x.disabled=true
		var x=document.getElementById("mySelect3")
		x.disabled=true
	}
	function makeEnable2()
	{	
		var x=document.getElementById("mySelect1")
		x.disabled=false
		var x=document.getElementById("mySelect2")
		x.disabled=false
		var x=document.getElementById("mySelect3")
		x.disabled=true
	}
	function makeEnable3()
	{
		var x=document.getElementById("mySelect1")
		x.disabled=true
		var x=document.getElementById("mySelect2")
		x.disabled=true
		var x=document.getElementById("mySelect3")
		x.disabled=false
	}
	function onLoad()
	{
		var x=document.getElementById("mySelect1")
		x.disabled=true
		var x=document.getElementById("mySelect2")
		x.disabled=true
		var x=document.getElementById("mySelect3")
		x.disabled=true
	}
</script>
	<meta name="keywords" content="Ham Radio NA7KR">
	<meta http-equiv="Content-Type" content="text/html; charset=us-ascii">
	<meta name="description" content="<?php echo $myCall ?> Ham Radio LogBook"> 
	<meta http-equiv="content-type" content="text/html;charset=UTF-8"> 
	<meta name="revisit-after" content="30 days">
	<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
	<?php
		include_once "config.php";
		include "counter.php";
		include('lock.php');
		include('style.php');
		$link = mysql_connect($dbhost, $dbuname, $dbpass) or die ('Cannot connect to the database: ' . mysql_error());
		mysql_select_db($dbnameHRD) or die ('Cannot connect to the database: ' . mysql_error());
	?>
<title><?php echo $myCall ?> Ham Radio LogBook Upload / config</title> 
</head>

<body onload="onLoad();" span class="background1">
    <div class="auto-style1"> Hello welcome Admin Page..... 
		<span class="auto-style3"><br></span>
		<span class="auto-style4">My Call is <?php echo $myCall ?></span><br>
    </div>	
<html> 
	<h1>Welcome Admin <?php echo $login_session; ?></h1> 
	<h2><a href="logout.php">Sign Out</a></h2>
	
	<br><br>
    <form enctype="multipart/form-data" class="Txt_upload" method='POST'  action='welcome.php'>
			<input type="radio" value='1' name="Log" onclick="makeEnable1()" > Upload Awards
			<input type="radio" value='2' name="Log" onclick="makeEnable2()" > Upload Paper
			<input type="radio" value='3' name="Log" onclick="makeEnable2()" > Upload Paper Back
			<input type="radio" value='4' name="Log" onclick="makeEnable3()" > Look contact number
			<br>
			<table width="30%" border="0" cellpadding="0" cellspacing="0"  >
				<tr valign="top">
					<td style="border-width : 0px;"><label>Enter Contact Number :</label></td>
					<td style="border-width : 0px;"><input type="text"  id='mySelect2' name="contactno" ></td>
				</tr>
				<tr valign="top">
					<td style="border-width : 0px;"><label>Enter Callsign :</label></td>
					<td style="border-width : 0px;"><input type="text"  id='mySelect3' name="callsign" ></td>
				</tr>
				<tr valign="top">
					<td style="border-width : 0px;"><label>Select file :</label></td>
					<td style="border-width : 0px;"><input type='file' id='mySelect1' name='file' /></td>
				</tr>
			</table>
			<br>
            <input type='submit' name='submit' value="upload here"/>
        </form>
    </body>
</html>

<?php
	if ($debug == "true")
		{
			echo "<pre>";
			print_r($_POST);
			echo "</pre>";
			echo "<pre>";
			print_r($_FILES);
			echo "</pre>";
		}
    if(isset($_FILES['file']))
	{	
		$LOG = $_POST['Log'];
		if ($LOG == 1)
		{
			$target_path = "Awards/";
		}
		else
		{
			$target_path = "cards/0-999/";
		}
		
		$upload_exts = "";
		$file_exts = array("jpg", "bmp", "jpeg", "gif", "png");
		$upload_exts = end(explode(".", $_FILES["file"]["name"]));
		if ((($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/png")
			|| ($_FILES["file"]["type"] == "image/pjpeg"))
			&& ($_FILES["file"]["size"] < 2000000)
			&& in_array($upload_exts, $file_exts))
		{
			if ($_FILES["file"]["error"] > 0)
			{
				echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
			}
			else
			{
				echo "Upload: " . $_FILES["file"]["name"] . "<br>";
				// Enter your path to upload file here
				if (file_exists($target_path . $_FILES["file"]["name"]))
			{
				echo "<div class='error'>"."(".$_FILES["file"]["name"].")". " already exists. "."</div>";
			}
			else
			{
				move_uploaded_file($_FILES["file"]["tmp_name"], $target_path . $_FILES["file"]["name"]);
				echo "<div class='sucess'>"."Stored in: " . $target_path . $_FILES["file"]["name"]."</div>";	
			}
		}
		}
		else
		{
			echo "<div class='error'>Invalid file</div>";
		}
	}
	

	$LOG = $_POST['Log'];
	$callsign = $_POST['callsign'];
	echo "<br>";
	if ($LOG == 4)
		{
			$sql = "SELECT `COL_CALL` , `COL_BAND`,`COL_TIME_OFF`,`COL_PRIMARY_KEY`FROM `TABLE_HRD_CONTACTS_V01` WHERE `COL_CALL` ='$callsign'" ;
			$query1 = mysql_query($sql);
			while($info = mysql_fetch_array( $query1 ))
				{
					$QSLWORKED  .=$info[0] . ' Was worked on the '  .$info[1] . ' Band on ' . date("jS M Y",strtotime($info[2])) . ' Contact number is: '.$info[3] . '<br>';
				}
		}
		echo $QSLWORKED  ;
		
	
?>
</body>
</html>