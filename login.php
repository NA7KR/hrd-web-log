<?php
// select_awards.php
/*
Copyright © 2024 NA7KR Kevin Roberts. All rights reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
session_start();
$title = "Admin Login";

// Set error reporting to display all errors
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');

// Include necessary files
include_once("../config.php");
require_once('backend/db.class.php');
require_once("backend/backend.php");
require_once("backend/querybuilder.php");
include('backend/header.php');

// Create a new instance of the Db class


$db = new Db();






if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $query = getUserByUsername($username);
        $params = array(':username' => $username);
        try {
            $results = $db->select($query, $params); // Execute select query
        } catch (Exception $e) {
            echo "Query: " . $query ."<br>";
            echo "Error executing query: " . $e->getMessage();
        }

        if ($results && count($results) == 1) {
            $user = $results[0];
            $hashed_password = hashPassword($password, $user['salt']);
            if ($hashed_password === $user['password']) {
                $_SESSION['username'] = $username;
                $_SESSION['user_role'] = $user['role']; // Assuming 'role' is stored in the database
            
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }
    } catch (PDOException $e) {
        // Log database errors
        error_log("Database Error: " . $e->getMessage());
        $error = "An error occurred while processing your request. Please try again later.";
    }
}


?>


<div class="form-container">
    <h2>Login</h2>
    <?php if(isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="post" action="">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
    <?php
    // Display admin features if the user is an admin
    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
        echo "<p>Welcome, Admin! You have access to admin features.</p>";
        // Add your admin-specific content here
    }
    ?>

