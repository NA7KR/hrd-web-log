<html>
    <head>
        <title>Register</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
    </head>
    <body onload="onLoad();" class="background1">
        <div id="loginForm">
            <?php
            /*             * *************************************************************************
             * 			NA7KR Log Program 
             * ************************************************************************* */

            /*             * *************************************************************************
             *
             *   This program is free software; you can redistribute it and/or modify
             *   it under the terms of the GNU General Public License as published by
             *   the Free Software Foundation; either version 2 of the License, or
             *   (at your option) any later version.
             *
             * ************************************************************************* */
            require("common.php");
            $query = "SELECT * FROM `users` ";
            try {
                $stmt = $db->prepare($query);
                $result = $stmt->execute($query_params);
            } catch (PDOException $ex) {
                die("Failed to run query: " . $ex->getMessage());
            }
            $row = $stmt->fetch();
            if ($row) {
                header("Location: login.php");
                die("Admin Account is Registered");
                die("Redirecting to login.php");
            } else {
                if (!empty($_POST)) {
                    if (empty($_POST['username'])) {
                        die("Please enter a username.");
                    }
                    if (empty($_POST['password'])) {
                        die("Please enter a password.");
                    }
                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        die("Invalid E-Mail Address");
                    }
                    $query = "  SELECT 1 FROM users WHERE  username = :username ";
                    $query_params = array(':username' => $_POST['username']);
                    try {
                        $stmt = $db->prepare($query);
                        $result = $stmt->execute($query_params);
                    } catch (PDOException $ex) {
                        die("Failed to run query: " . $ex->getMessage());
                    }
                    $row = $stmt->fetch();
                    if ($row) {
                        die("This username is already in use");
                    }
                    $query = "SELECT 1 FROM users WHERE email = :email";
                    $query_params = array(':email' => $_POST['email']
                    );
                    try {
                        $stmt = $db->prepare($query);
                        $result = $stmt->execute($query_params);
                    } catch (PDOException $ex) {
                        die("Failed to run query: " . $ex->getMessage());
                    }
                    $row = $stmt->fetch();
                    if ($row) {
                        die("This email address is already registered");
                    }
                    $query = "  INSERT INTO users ( username, password, salt, email ) VALUES ( :username, :password, :salt, :email  ) ";
                    $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
                    $password = hash('sha256', $_POST['password'] . $salt);
                    for ($round = 0; $round < 65536; $round++) {
                        $password = hash('sha256', $password . $salt);
                    }
                    $query_params = array(':username' => $_POST['username'], ':password' => $password, ':salt' => $salt, ':email' => $_POST['email']);
                    try {
                        $stmt = $db->prepare($query);
                        $result = $stmt->execute($query_params);
                    } catch (PDOException $ex) {
                        die("Failed to run query: " . $ex->getMessage());
                    }
                    header("Location: login.php");
                    die("Redirecting to login.php");
                }
                ?> 

                <form action="register.php" method="post">
                    <div id="formHeader">Register for Admin</div>
                    <div id="formBody">
                        <div class="formField"><input type="call" name="username" required placeholder="Call" /></div>
                        <div class="formField"><input type="email" name="email" required placeholder="Email" /></div>
                        <div class="formField"><input type="password" name="password" required placeholder="Password" /></div>
                        <div><input type="submit" value="Register" class="customButton" /></div>
                    </div>
                    <div id='userNotes'>
                        <!-- New here? <a href='register.php'>Register for free</a> -->
                    </div>
                </form>
                <?php
            }
            ?>