<?php
//qslprintpre.php
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
require_once ("../config.php");
require_once ("backend/db.class.php");
require_once("backend/querybuilder.php");

// Check if Imagick extension is loaded
if (!extension_loaded('imagick')) {
    // Display error message if Imagick extension is not installed
    echo "Imagick extension is not installed. Please install Imagick to use this feature.";
    exit;
}

// Check if POST data is empty
if (empty($_POST)) {
    // Redirect to received.php if no POST data is found
    header('Location: home.php');
    exit;
} 

// Sanitize the call variable against injection
$call = isset($_POST["call"]) ? $_POST["call"] : 0;

if ($call == 0) {
    // Display error message for invalid parameters'
    echo "<html><head><title>ERROR</title></head><body><h1>Invalid Parameters Error E1</h1></body></html>";
    exit;
}

// Build the "IN" array for the SELECT statement, checking the items for safety
$qs = implode(",", array_filter($_POST['qq'], 'strlen'));

if (empty($qs)) {
    // Display error message for invalid parameters
    echo "<html><head><title>ERROR</title></head><body><h1>Invalid Parameters Error E2</h1></body></html>";
    exit;
}


$query = getSelect_Pre($qs);
//"SELECT *, DATE_FORMAT(`" . DB_COL_TIME_ON . "`, '%m/%d/%Y - %H:%i') AS `kCOL_TIME_ON` FROM " . DB_TABLE_HRD . " WHERE " . DB_COL_PRIMARY_KEY ." IN ($qs) ORDER BY ". DB_COL_TIME_ON . " ASC"; 
//echo $query;
// Execute the query securely
try {
    $results = $db->select($query);
} catch (Exception $e) {
    echo "Query: " . $query ."<br>";
    echo "Error executing query: " . $e->getMessage();
}

// Check if $results is not an array or if it's empty
if (!is_array($results) || empty($results)) {
    // Output a message indicating no results were found
    echo "<p class=\"lead\">Sorry, no QSOs have been loaded into the database for that callsign yet.</p>";
    // Exit the script
    exit;
}

// Check if $results is an array and has elements
if (is_array($results) && count($results) < 1) {
    // Output a message indicating no results were found
    echo "<p class=\"lead\">Sorry, no QSOs have been loaded into the database for that callsign yet.</p>";
    // Exit the script
    exit;
}