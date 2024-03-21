<?php
// select_ecards.php
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
$first = "false";
$first = htmlspecialchars($_POST["1st"]);
if ($first != true) {
    // header( 'Location: received.php' ) ;
}
include("../config.php");
require_once('backend/db.class.php');
require_once("backend/backend.php");
require_once("backend/querybuilder.php");
require_once("backend/filecheck.php");
require_once('backend/email.php');


$i = 0; //style counter
$x = 0; //
$counter = 0;
$data = "";
$SUBMIT = "false";
$find = '.jpg';
$fileMultiply = 1000; // Change to 1000 for 0-999, 2000 for 1000-1999, and so on

$query = getSelect_ecard();

try {
    $results = $db->select($query); // Execute select query
} catch (Exception $e) {
    echo "<br>Query: " . $query ."<br><br>";
    echo "Error executing query: " . $e->getMessage();
}
$data .= "<div class='centered-content'>";
$data .= "<div class='table-container'>";
$data .= "<table border='0' class='custom-table small-left-table'><tbody><tr>"
    . "<th>Log ID</th>" . "<th>Call</th>" . "<th>Card</th>"
    . "<th>Back</th>" . "</tr><tr bgcolor='#5e5eff'>" . PHP_EOL;

 $fileNameF = "";
 $fileNameB = "";
 $fileNameFT = "";
 $fileNameBT = "";

foreach ($results as $row) {
    $data .= "<td>" . $row['Log ID'] . "</td>" . PHP_EOL;
    $data .= "<td>" . $row['Call'] . "</td>" . PHP_EOL;

    // Calculate the file group range
    $groupNumber = floor($row['Log ID'] / $fileMultiply);
    $FileNoGroup = $groupNumber * $fileMultiply;
    $fileNoGroupHigh = ($groupNumber + 1) * $fileMultiply - 1;

    // Adjust filePath based on the calculated group range
    $filePath = "cards/" . $FileNoGroup . "-" . $fileNoGroupHigh;

    // Process Card and Back files
    $debug = false;
    $fileNameF= $row['Card']; 
    $fileNameB = $row['Back'];
    $filepathF = check_file($filePath, $fileNameF,false. $debug);
    $filepathB = check_file($filePath, $fileNameB, false,$debug);
    $filepathFT = check_file($filePath . "/thumbs/" , $fileNameF,true, $debug);
    $filepathBT = check_file($filePath . "/thumbs/" , $fileNameB,true, $debug);
 
    $data .= "<td style='text-align: center;'><a href='$filepathF'><img src='$filepathFT' alt='$fileNameF' ></a></td>" . PHP_EOL;
    $data .= "<td style='text-align: center;'><a href='$filepathB'><img src='$filepathBT' alt='$fileNameB' ></a></td>" . grid_style($i) . PHP_EOL;
    $counter++;
    $i++;
}

$data .= "</table>" . PHP_EOL;
$data .= "</div>"; // Close the table-container div
$data .= "<p style='text-align: center'><BR> Counter " . $counter . "</p><BR>" . PHP_EOL;
echo $data;

?>