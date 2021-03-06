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
        <title>User Page</title>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
    </head>
    <body " class="background1">
        <div id="loginForm"></div>
        <?php
        if (empty($_SESSION['user'])) {
            header("Location: login.php");
            die("Redirecting to login.php");
        }
        require_once(__DIR__ . '/../db.class.php');
        $db = new Db();
        $id_lookup = $db->query("SELECT id, username, email FROM $dbnameWEB.users");
        ?> 
        <h1>Memberlist</h1> 
        <table> 
            <tr> 
                <th>ID</th> 
                <th>Username</th> 
                <th>E-Mail Address</th> 
            </tr> 
            <?php foreach ($id_lookup as $row): ?> 
                <tr> 
                    <td><?php echo $row['id']; ?></td> <!-- htmlentities is not needed here because $row['id'] is always an integer --> 
                    <td><?php echo htmlentities($row['username'], ENT_QUOTES, 'UTF-8'); ?></td> 
                    <td><?php echo htmlentities($row['email'], ENT_QUOTES, 'UTF-8'); ?></td> 
                </tr> 
            <?php endforeach; ?> 
        </table> 
<a href="welcome.php">Go Back</a><br /> 