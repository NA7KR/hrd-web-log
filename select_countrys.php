<?php
// select_countrys.php
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
// Initialize variables
$first = "false";
$SUBMIT = "false";
$first = htmlspecialchars($_POST["1st"]); // Sanitize input
if ($first <> True) {
    header('Location: received.php'); // Redirect if condition is met
}
include("../config.php"); // Include configuration file
require_once('backend/db.class.php'); // Require database class file
require_once("backend/backend.php"); // Require backend file

$i = 0; // Style counter
$x = 0; //
$FileNoGroup = 0;
$find = '.jpg';
$fileMultiply = 1000; // Corrected variable name from 'fileMutiply' to 'fileMultiply'
$counter = 0;
$data = "";

// Check if form is submitted
if (isset($_POST['Submit1'])) {
    $LOG = htmlspecialchars($_POST["Log"]); // Sanitize input
    if (isset($_POST['Submit'])) {
        $SUBMIT = htmlspecialchars($_POST["Submit"]); // Sanitize input
    }
    if (isset($_POST['Band'])) {
        $BAND = htmlspecialchars($_POST["Band"]); // Sanitize input
    }

    if (isset($_POST['Mode'])) {
        $MODE = htmlspecialchars($_POST["Mode"]); // Sanitize input
    }
    if (isset($_POST['optionlist'])) {
        $INPUT = htmlspecialchars($_POST["optionlist"]); // Sanitize input
    }

    // Build hidden input fields for form submission
    $data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
}

$query = getSelect_Countries(); // Get select query for countries
if ($SUBMIT == "true") {
    if ($INPUT == "input_band") {
        $BAND = safe("%" . $BAND . "%"); // Sanitize input
        $query = str_replace("__REPLACE__", "where COL_BAND like :band", $query);
        $params = ['band' => $BAND];
    } elseif ($INPUT == "input_mode") {
        $MODE = safe("%" . $MODE . "%"); // Sanitize input
        $MODE = str_replace("USB", "SSB", $MODE);
        $MODE = str_replace("LSB", "SSB", $MODE);
        $query = str_replace("__REPLACE__", "where COL_MODE like :mode or COL_MODE like 'USB' or COL_MODE like 'LSB'", $query);
        $params = ['mode' => $MODE];
    } elseif ($INPUT == "input_none") {
        $query = str_replace("__REPLACE__", "", $query);
        $params = [];
    }
    try {
        $results = $db->select($query, $params); // Execute select query
    } catch (Exception $e) {
        echo "Query: " . $query ."<br>";
        echo "Error executing query: " . $e->getMessage();
    }
    $data .= "<div class='centered-content'>" .PHP_EOL;; // Add a container div
    $data .= "<table class='custom-table' border='0'>"
        . "<tbody><tr>"
        . "<th>Country</th></tr><tr bgcolor='#5e5eff'>" . PHP_EOL;
    foreach ($results as $row) {
        $data .= "<td>" . $row['Countries Worked'] . "</td>" . PHP_EOL . grid_style($i) . PHP_EOL; // Append table row data
        $counter++;
        $i++;
    }
    $data .= "</table>" . PHP_EOL; // Close table
    $data .= "<p style='text-align: center'><BR> Counter " . $counter . "</p><BR>" . PHP_EOL; // Display counter
    $data .= OptionList(false, false, false, false, false, false) . PHP_EOL; // Append option list
  
} else {
    $data = '<table width=600 class="center2">' . PHP_EOL; // Start table
    $data .= '<tr><td>' . PHP_EOL; // Start table row
    $data .= OptionList(true, true, false, false, false, true) . PHP_EOL; // Append option list
    $data .= band() . PHP_EOL; // Append band input field
    $data .= mode() . PHP_EOL; // Append mode input field
    $data .= '</td></tr>' . PHP_EOL; // Close table row
    $data .= '</table>' . PHP_EOL; // Close table
    $data .= '<div class="c1">' . PHP_EOL; // Start div
    $data .= '<span class="auto-style5">' . PHP_EOL; // Start span
    $data .= 'none will return all<br>' . PHP_EOL; // Display message
    $data .= '<Input type = "Submit" Name = "Submit1" VALUE = "Submit"></span></div></FORM><BR>' . PHP_EOL; // Submit button
}
echo $data; // Output data

?>
