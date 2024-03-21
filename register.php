<?php
// Start the session
session_start();

// Set error reporting to display all errors
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');

// Include necessary files
require_once("backend/backend.php");
require_once('../config.php');
require_once("backend/querybuilder.php");
require_once("backend/db.class.php");
$db = new Db();

function generateSalt() {
    return substr(md5(uniqid(rand(), true)), 0, 50);
}

function hashPassword($password, $salt) {
    return hash('sha256', $password . $salt);
}

function registerUser($username, $email, $password) {
    $db = new Db();
    $salt = generateSalt();
    $hashed_password = hashPassword($password, $salt);
    $sql = "INSERT INTO users (username, email, password, salt) VALUES (:username, :email, :password, :salt)";
    $params = array(':username' => $username, ':email' => $email, ':password' => $hashed_password , ':salt' => $salt);

    try {
        $affectedRows = $db->executeSQL($sql, $params, 'insert', 'users'); // Execute insert query
        if ($affectedRows > 0) {
            return "User added successfully!";
        } else {
            return "Failed to add user.";
        }
    } catch (Exception $e) {
        throw new Exception("Error adding user: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Perform user registration
    try {
        $success_message = registerUser($username, $email, $password);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include ("backend/header.php");
?>

<div class="form-container">
    <h2>Register</h2>
    <?php if(isset($error)) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>
    <?php if(isset($success_message)) { ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php } ?>
    <form method="post" action="">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Register">
    </form>
</div>
</body>
</html>
