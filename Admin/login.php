<html>
	<head>
		<title>login page</title>
		<link type="text/css" rel="stylesheet" href="css/style.css" />
	</head>
<body " class="background1">
<div id="loginForm">
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
require("common.php"); 
$submitted_username = ''; 

$query = "SELECT * FROM `users` ";  
	try 
	{ 
		$stmt = $db->prepare($query); 
		$result = $stmt->execute($query_params); 
	} 
	catch(PDOException $ex) 
	{ 
		die("Failed to run query: " . $ex->getMessage()); 
	} 
	$row = $stmt->fetch(); 
	if($row) 
	{ 
		if(!empty($_POST)) 
		{
			$query = "  SELECT id, username, password, salt, email FROM users WHERE username = :username"; 
			$query_params = array( ':username' => $_POST['username'] ); 
			try 
			{ 
				$stmt = $db->prepare($query); 
				$result = $stmt->execute($query_params); 
			} 
			catch(PDOException $ex) 
			{  
				die("Failed to run query: " . $ex->getMessage()); 
			} 
			$login_ok = false; 
			$row = $stmt->fetch(); 
			if($row) 
			{ 
				$check_password = hash('sha256', $_POST['password'] . $row['salt']); 
				for($round = 0; $round < 65536; $round++) 
				{ 
					$check_password = hash('sha256', $check_password . $row['salt']); 
				} 
				 
				if($check_password === $row['password']) 
				{ 
					$login_ok = true; 
				} 
			} 
			if($login_ok) 
			{  
				unset($row['salt']); 
				unset($row['password']); 
				$_SESSION['user'] = $row; 
				header("Location: welcome.php"); 
				die("Redirecting to: welcome.php"); 
			} 
			else 
			{
				echo "<div>Access denied. <a href='login.php'>Back.</a></div>";
				$submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); 
			} 
		}     

		else
		{
			?>
			<form action="login.php" method="post">
				<div id="formHeader">Website Login.</div>
				<div id="formBody">
					<div class="formField"><input type="call" name="username" required placeholder="Call" /></div>
					<div class="formField"><input type="password" name="password" required placeholder="Password" /></div>
					<div><input type="submit" value="Login" class="customButton" /></div>
				</div>
				<div id='userNotes'>
					<!-- New here? <a href='register.php'>Register for free</a> -->
				</div>
			</form>
			<?php
		}
	}
	else
	{
		header("Location: register.php"); 
        die("Redirecting to register.php"); 	
    }
?>
				
</div>
</body>
</html>