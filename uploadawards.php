<?php
session_start();

$title = "Admin Area Add Awards"; // Title for the page

if (!isset($_SESSION['username'])) { // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit;
}

$base = __DIR__ . "/"; // Base directory
$debug = 'false'; // Debug mode

include 'backend/header.php'; // Include header file
include_once '../config.php'; // Include configuration file
require_once 'backend/db.class.php'; // Require database class
require_once 'backend/java.php'; // Require Java file
require_once 'backend/querybuilder.php'; // Require query builder file
include 'backend/filecheck.php'; // Include file checking file

error_reporting(E_ALL); // Report all errors

?>

<div style="text-align: center;">
    <div class='centered-content'>
        <!-- Form for uploading awards -->
        <form enctype="multipart/form-data" class="Txt_upload" method='POST' action='uploadawards.php'>
            <div style="margin-bottom: 10px;">
                <label for="awardsdes">Enter Description:</label>
                <input type="text" name="awardsdes" style="margin-left: 10px; max-width: 300px;">
            </div>
            <div style="margin-bottom: 10px;">
                <label for="file">Select file:</label>
                <input type="file" name="file" style="margin-left: 10px;">
            </div>
            <br>
            <input type='submit' name='submit' value="Upload Here">
        </form>
    </div>
</div>

<?php

if ($debug == "true") {
    // Debug information
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
}

if (isset($_POST['submit']) && $_POST['submit'] === 'Upload Here') {
    
    $filePath = $base . "Awards/"; // Path to save uploaded files
    $FileName = $_FILES["file"]["name"]; // Name of the uploaded file
    $AwardsDes = $_POST['awardsdes']; // Description of the award
    
    echo "<div class='centered-content'>";
    $upload_exts = ""; // Variable to store uploaded file extensions
    $file_exts = array("jpg", "jpeg",  "JPG"); // Allowed file extensions
    $upload_exts_tp = explode(".", $_FILES["file"]["name"]); // Get file extension
    $upload_exts = end($upload_exts_tp); // Get last element of the array which is the file extension
    
    if ((($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 2000000) && in_array($upload_exts, $file_exts)) {
        // Check if file type, size, and extension are valid
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br>"; // Display error code if any
        } else {
            if (is_writable($filePath)) {
                echo "The folder is writable.<br>"; // Check if folder is writable
            } else {
                echo "Cannot save to: " . $filePath . "<br>"; // Display error if folder is not writable
                exit;        
            }
            echo "Upload: " . $FileName . "<br>"; // Display uploaded file name
            
            if (file_exists($filePath . $FileName)) {
                echo "<div class='error'>" . $filePath . $FileName . " already exists. " . "</div>"; // Display error if file already exists
            } else {
                move_uploaded_file($_FILES["file"]["tmp_name"], $filePath . $FileName); // Move uploaded file to destination folder
                echo "<div class='success'>" . "Stored in: " . $filePath . $FileName . "</div>"; // Display success message
                $pathToThumbs = $filePath . "/thumbs/"; // Path to thumbnails folder
                $dir = opendir($pathToThumbs); // Open thumbnails folder
                $img = imagecreatefromjpeg("{$filePath}/{$FileName}"); // Create image resource from uploaded file
                $width = imagesx($img); // Get image width
                $height = imagesy($img); // Get image height
                $thumbWidth = 100; // Thumbnail width
                $new_height = floor($height * ($thumbWidth / $width )); // Calculate new height for thumbnail
                $tmp_img = imagecreatetruecolor($thumbWidth, $new_height); // Create new true color image for thumbnail
                imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $thumbWidth, $new_height, $width, $height); // Resize and copy image for thumbnail
                imagejpeg($tmp_img, "{$pathToThumbs}{$FileName}"); // Save thumbnail
                chmod("{$pathToThumbs}{$FileName}", 0644); // Set permissions for thumbnail
                closedir($dir); // Close thumbnails folder
                echo "<br> Saved File <br>"; // Display message
                
                try {
                    $query = setSelect_Awards(); // Set query for selecting awards
                    $params = array($AwardsDes, $FileName); // Parameters for the query
                    $action = 'update'; // Action for the query
                    $tablename = DB_Table_optout; // Table name
                    
                    try {
                        $affectedRows = $db->executeSQL($query, $params,  $action, $tablename); // Execute the SQL query
                    } catch (Exception $e) {
                        echo "Query: " . $query ."<br>"; // Display query
                        echo "Error executing query: " . $e->getMessage(); // Display error message
                    }
                    
                    if ($debug == "true") {
                        echo "Query: " . $query . "Prams: " . $pathToThumbs . " and " . $FileName ; // Display debug information
                    }
                    
                    if ($affectedRows > 0) {
                        ?>
                        <!-- Display success message -->
                        <div class="container" style="display: flex; justify-content: center; align-items: center;">
                            <?php
                            $message = '<h1 style="color:Blue;">Added</h1>'; 
                            echo $message . "</div>";
                    } else {
                        echo "Update failed ". $query; // Display update failure message
                    }
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage(); // Display error message
                }
            }
        }
    }
}
include 'backend/footer.php'; // Include footer file
?>
