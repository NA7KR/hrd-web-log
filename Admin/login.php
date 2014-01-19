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
        <title>login page</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
    </head>
    <body " class="background1">
        <div id="loginForm">
            <?php
            require_once(__DIR__ . '/../lookup.class.php');
            $Lookup = new Lookup();
            $submitted_username = '';
            d($Lookup->count('id', 'users'), "User");
            
            function d($value, $Key) {
                if ($value >= 1) {
                    if (!empty($_POST['username'])) {
                        $db = new Db();
                        $Key = $_POST['username'];
                        $id_lookup = $db->row("SELECT id, username, password, salt, email FROM users WHERE username =  :f", array("f" => $Key));

                        if ($id_lookup) {
                            $check_password = hash('sha256', $_POST['password'] . $id_lookup['salt']);
                            for ($round = 0; $round < 65536; $round++) {
                                $check_password = hash('sha256', $check_password . $id_lookup['salt']);
                            }
                            if ($check_password == $id_lookup['password']) {
                                $login_ok = true;
                            }
                        }
                        if ($login_ok) {
                            unset($id_lookup['salt']);
                            unset($id_lookup['password']);
                            $_SESSION['user'] = $id_lookup;
                            header("Location: welcome.php");
                            die("Redirecting to: welcome.php");
                        } else {
                            echo "<div>Access denied. <a href='login.php'>Back.</a></div>";
                            $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
                        }
                    } else {
                        ?>
                        <form action="login.php" method="post">
                            <div id="formHeader">Website Login.</div>
                            <div id="formBody">
                                <br>
                                <div class="formField"><input type="call" name="username" required placeholder="Call" /></div>
                                <br>
                                <div class="formField"><input type="password" name="password" required placeholder="Password" /></div>
                                <br><br>
                                <div><input type="submit" value="Login" class="customButton" /></div>
                            </div>
                            <div id='userNotes'>
                                <!-- New here? <a href='register.php'>Register for free</a> -->
                            </div>
                        </form>
            <?php
        }
    } else {
        header("Location: register.php");
        die("Redirecting to register.php");
    }
}
?>
        </div>
    </body>
</html>