<?php 
{
	include('lock.php');
	include_once('config.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php
		include_once "config.php";
		include "counter.php";
	?>
	<title><?php echo $myCall ?> Ham Radio LogBook Upload / config</title> 
	<meta http-equiv="Content-Type" content="text/html; charset=us-ascii">
	<meta name="description" content="<?php echo $myCall ?> Ham Radio LogBook"> 
	<meta http-equiv="content-type" content="text/html;charset=UTF-8"> 
	<meta name="revisit-after" content="30 days">
	<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
</head>

<body onload="onLoad();" span class="background1">
<?php include_once("analyticstracking.php") ?>
    <div class="auto-style1"> Hello welcome Admin Page <span class="auto-style3"><br>
        </span><span class="auto-style4">My Call is <?php echo $myCall ?></span><br>
    </div>
	
<html> 
	<h1>Welcome Admin <?php echo $login_session; ?></h1> 
	<h2><a href="logout.php">Sign Out</a></h2>

    <form enctype="multipart/form-data" class="Txt_upload" method='POST'  action='welcome.php'>
            <label for="file">Enter upload here Awards:            </label><br>
            <input type='file' name='file'/><br><br>
			
			<label for="filecard">Enter upload here Paper Card:     </label><br>
            <input type='file' name='filecard'/><br><br>
			
			<label for="fileback">Enter upload here Paper Card Back:</label><br>
            <input type='file' name='fileback'/><br><br>
			
            <input type='submit' name='submit' value="upload here"/>
        </form>
    </body>
</html>

<?php
    if(isset($_FILES['file']))
	{
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
				if (file_exists("Awards/" .
				$_FILES["file"]["name"]))
			{
				echo "<div class='error'>"."(".$_FILES["file"]["name"].")". " already exists. "."</div>";
			}
			else
			{
				move_uploaded_file($_FILES["file"]["tmp_name"],
				"Awards/" . $_FILES["file"]["name"]);
				echo "<div class='sucess'>"."Stored in: " .
				"Awards/" . $_FILES["file"]["name"]."</div>";
				
			}
		}
		}
		else
		{
			echo "<div class='error'>Invalid file</div>";
		}
	}
?>
</body>
</html>
