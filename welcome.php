<?php include('lock.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Welcome Admin</title>
</head>

<body>
<h1>Welcome Admin <?php echo $login_session; ?></h1> 

<h2><a href="logout.php">Sign Out</a></h2>



<html> 
<form action="welcome.php" method="post" enctype="multipart/form-data"> 
<label for="file">Filename:</label> 
<input type="file" name="file" id="file"><br> 
<input type="submit" name="submit" value="Submit"> 
</form> 

<?php
	if (isset($_POST['Submit']))
	{
		echo "<style>";
		echo ".sucess";
		echo "{color:#088A08;}";
		echo ".error";
		echo "{color:red;}";
		echo "</style>";

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
				echo "Type: " . $_FILES["file"]["type"] . "<br>";
				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
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
