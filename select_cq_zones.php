<?php
// select_cq_zones.php
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
// Check if the form has been submitted and if the value of "1st" is true
if (!isset($_POST["1st"]) || $_POST["1st"] !== "true") {
    // Redirect to received.php if the condition is not met
    header('Location: received.php');
    exit; // Stop further execution
}

// Include necessary files
include("../config.php");
require_once('backend/db.class.php');
require_once("backend/backend.php");
require_once("backend/querybuilder.php");

// Initialize variables
$i = 0; // Style counter
$x = 0; //
$FileNoGroup = 0;
$find = '.jpg';
$fileMultiply = 1000;
$data = "";
$SUBMIT = "false";
$counter = 0;

// Check if form is submitted
if (isset($_POST['Submit1'])) {
    $LOG = htmlspecialchars($_POST["Log"]);
    if (isset($_POST['Submit'])) {
        $SUBMIT = htmlspecialchars($_POST["Submit"]);
    }
    if (isset($_POST['Band'])) {
        $BAND = htmlspecialchars($_POST["Band"]);
    }
    if (isset($_POST['Mode'])) {
        $MODE = htmlspecialchars($_POST["Mode"]);
    }
    if (isset($_POST['optionlist'])) {
        $INPUT = htmlspecialchars($_POST["optionlist"]);
    }
    
    // Build hidden input fields for form submission
    $data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
};

$query = getSelect_CQ_Zones(); // Get select query for CQ zones

// Check if form is submitted
if ($SUBMIT == "true") {
    if ($INPUT == "input_band") {
        $TEXT = $BAND;
        $BAND = safe("%" . $BAND . "%"); // Sanitize input
        
        // Get select query for CQ zones based on band
        $query = getSelect_CQ_ZonesBand();
        
        // Replace placeholder in the query with band value
        $query = str_replace("__REPLACE__", " $BAND ", $query);
        
        try {
            $results = $db->select($query); // Execute select query
        } catch (Exception $e) {
            echo "Query: " . $query ."<br>";
            echo "Error executing query: " . $e->getMessage();
        }
        
        // Output results in a table
        $data .= "<div class='centered-content'>";
        $data .= "<table class='custom-table' border='0'><tbody><tr>CQ Zone to Work $TEXT</th></tr>";
        foreach ($results as $row) {
            $data .= "<td>" . $row['CQ Zone to Work'] . "</td><td>" . $row['Mode'] . "</td>" . grid_style($i) . PHP_EOL;
            $i++;
            unset($row); // break the reference with the last element
        }
    } elseif ($INPUT == "input_mode") {
        $TEXT = $MODE;
        $MODE = safe("%" . $MODE . "%"); // Sanitize input
        
        // Get select query for CQ zones based on mode
        $query = getSelect_ZoneMode();
        $query = str_replace("__REPLACE__", " $MODE ", $query); // Replace placeholder in the query with mode value
        
        try {
            $results = $db->select($query); // Execute select query
        } catch (Exception $e) {
            echo "<br>Query: " . $query ."<br><br>";
            echo "Error executing query: " . $e->getMessage();
        }
        
        // Output results in a table
        $data .= "<div class='centered-content'>";
        $data .= "<table class='custom-table' border='0'><tbody><tr>CQ Zone to Work $TEXT</th></tr>";
        foreach ($results as $row) {
            $data .= "<td>" . $row['CQ Zone to Work'] . "</td><td>" . $row['Band'] . "</td>" . grid_style($i) . PHP_EOL;
            $i++;
            unset($row); // break the reference with the last element
        }
    } elseif ($INPUT == "input_none") {
        // Get select query for all CQ zones
        $query = getSelect_ZoneNone($query);
        try {
            $results = $db->select($query); // Execute select query
        } catch (Exception $e) {
            echo "Query: " . $query ."<br>";
            echo "Error executing query: " . $e->getMessage();
        }
        
        // Output results in a table
        $data .= "<div class='centered-content'>";
        $data .= "<table class='custom-table' border='0'><tbody><tr>CQ Zone to Work </th></tr>";
        foreach ($results as $row) {
            $data .= "<td>" . $row['CQ Zone to Work'] . "</td>" . grid_style($i) . PHP_EOL;
            $i++;
            unset($row); // break the reference with the last element
        }
    }
    $data .= "</table><br><br>" . PHP_EOL;
    $data .='<div class="c1">' . PHP_EOL;
    $data .='<span class="auto-style5">' . PHP_EOL;
    $data .= "Count " .$i;
    $data .=OptionList(false, false, false, false, false, false) . PHP_EOL;
} else {
    // If form is not submitted, display form fields
    $data = '<table width=600 class="center2">' . PHP_EOL;
    $data .='<tr><td>' . PHP_EOL;
    $data .=OptionList(true, true, false, false, false, true) . PHP_EOL;
    $data .=band() . PHP_EOL;
    $data .=mode() . PHP_EOL;
    $data .='</td></tr>' . PHP_EOL;
    $data .='</table>' . PHP_EOL;
    $data .='<div class="c1">' . PHP_EOL;
    $data .='<span class="auto-style5">' . PHP_EOL;
    $data .='none will return all<br>' . PHP_EOL;
    $data .='<Input type = "Submit" Name = "Submit1" VALUE = "Submit"></span></div></FORM><BR>' . PHP_EOL;
}

// Output the data
echo $data;
   
?>
