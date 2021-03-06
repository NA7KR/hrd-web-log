<?php
/* * *************************************************************************
 * 			NA7KR Log Program 
 * ************************************************************************* */

/* * *************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * ************************************************************************* */
session_start();
?>
<html>
    <head>
        <title>Edit Account</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
    </head>
    <body " class="background1">
        <div id="loginForm">
            <?php
            require_once(__DIR__ . '/../db.class.php');
            $db = new Db();
            if (empty($_SESSION['user'])) {
                header("Location: login.php");
                die("Redirecting to login.php");
            }
            if (!empty($_POST)) {
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    die("Invalid E-Mail Address");
                }
                if ($_POST['email'] != $_SESSION['user']['email']) {
                    $email = $_POST['email'];
                    $id_lookup = $db->query("SELECT 1 FROM users WHERE email = $email");
                    if ($id_lookup) {
                        die("This E-Mail address is already in use");
                    }
                }
                if (!empty($_POST['password'])) {
                    $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
                    $password = hash('sha256', $_POST['password'] . $salt);
                    for ($round = 0; $round < 65536; $round++) {
                        $password = hash('sha256', $password . $salt);
                    }
                } else {
                    $password = null;
                    $salt = null;
                }
                $query_params = array(':email' => $_POST['email'], ':user_id' => $_SESSION['user']['id'],);
                if ($password !== null) {
                    $query_params[':password'] = $password;
                    $query_params[':salt'] = $salt;
                }


                $query = "UPDATE users SET email = :email ";
                if ($password !== null) {
                    $query .= ", password = :password , salt = :salt  ";
                }
                $update = $db->query(" .= WHERE  id = :user_id  ");

                $_SESSION['user']['email'] = $_POST['email'];
                header("Location: private.php");
                die("Redirecting to private.php");
            }
            ?> 
            <h1>Edit Account</h1> 
            <form action="edit_account.php" method="post"> 
                Username:<br> <b><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></b> <br><br> 
                E-Mail Address:<br> <input type="email" name="email" value="<?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>" >  <br><br> 
                Password:<br>  <input type="password" name="password" value="" ><br> 
                <i>(leave blank if you do not want to change your password)</i> <br><br> 
                <input type="submit" value="Update Account" /> 
            </form> 