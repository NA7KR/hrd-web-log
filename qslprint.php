<?php
// qslprint.php
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
error_reporting(E_ALL);
ini_set('display_errors', '1');

$title = "Mailed Cards";

include_once("../config.php");
require_once('backend/db.class.php');
require_once("backend/backend.php");
require_once("backend/querybuilder.php");
include('backend/header.php');

require("qslprintpre.php");

//echo "<pre>";
//var_dump($_POST);
//echo "</pre>";


// Initialize the ImageMagick item
$image = new Imagick('images/'. $qsl_template);
$image->setImageFormat('jpg');

// Draw the Callsign
$draw = new ImagickDraw();
$color = new ImagickPixel($qsl_c_font_color);
$draw->setFont($qsl_c_font);
$draw->setFontSize($qsl_c_font_size);
$draw->setFillColor($color);
$draw->setStrokeAntialias($qsl_c_font_aa);
$draw->setTextAntialias($qsl_c_font_aa);
if($qsl_callsign_center_gravity){
    $image->setGravity(imagick::GRAVITY_CENTER);
}
$call2 = $qsltxt  . $call;
$draw->annotation($qsl_callsign_horiz_offset, $qsl_callsign_vert_offset, $call2);
$image->drawImage($draw);

if($qsl_callsign_center_gravity){
    $image->setGravity(imagick::GRAVITY_NORTHWEST);
}

// Draw the QSO(s)
$draw = new ImagickDraw();
$color = new ImagickPixel($qsl_font_color);
$draw->setFont($qsl_font);
$draw->setFontSize($qsl_font_size);
$draw->setFillColor($color);
$draw->setStrokeAntialias($qsl_font_aa);
$draw->setTextAntialias($qsl_font_aa);

if($qsl_qso_center_gravity){
    $image->setGravity(imagick::GRAVITY_CENTER);
}   

//$row = $results->fetch_assoc();
// Assuming $results is a valid result set object, you can fetch data from it

// Fetching rows using the select function
try {
    $results = $db->select($query);
} catch (Exception $e) {
    echo "Query: " . $query ."<br>";
    echo "Error executing query: " . $e->getMessage();
}

// Check if $rows is not an array or if it's empty
if (!is_array($results) || empty($results)) {
    // Output a message indicating no results were found
    echo "<p class=\"lead\">Sorry, no QSOs have been loaded into the database for that callsign yet.</p>";
    // Exit the script
    exit;
}

// Iterate over each row in the result set
foreach ($results as $row) {
    if ($qsl_qso_verbose_rec) {
        $freqband = (strlen($row[DB_COL_FREQ]) > 0) ? sprintf("%.3f", $row[DB_COL_FREQ]/1000000) : $row[DB_COL_BAND];
        $rst = (is_int(strlen($row[$db_COL_RST_RCVD]))) ? $row[$db_COL_RST_RCVD] : ((strcasecmp($row[$db_COL_MODE], "CW") or strcmp($row[$db_COL_MODE], "cw")) ? "599" : "59");
        
        $qstring = sprintf("%s %sZ  Freq: %sMhz  RST: %s  Mode: %s",
            "", $row['kCOL_TIME_ON'], $freqband, $rst, $row[$db_COL_MODE]);
        
        $draw->annotation($qsl_horiz_offset, $qsl_vert_offset, $qstring);
    } else {
        $draw->annotation($qsl_horiz_offset, $qsl_vert_offset, $row['qsodate']);
        $draw->annotation($qsl_horiz_offset + $qsl_horiz_timeon_offset, $qsl_vert_offset, $row['kCOL_TIME_ON'] . "Z");
        
        $freqband = (strlen($row[$db_COL_FREQ]) > 0) ? sprintf("%.3f", $row[$db_COL_FREQ]/1000000) : $row[$db_COL_BAND];
        $draw->annotation($qsl_horiz_offset + $qsl_horiz_band_offset, $qsl_vert_offset, $freqband);
        
        $rst = (is_int(strlen($row[$db_COL_RST_RCVD]))) ? $row[$db_COL_RST_RCVD] : ((strcasecmp($row[$db_COL_MODE], "CW") or strcmp($row[$db_COL_MODE], "cw")) ? "599" : "59");
        $draw->annotation($qsl_horiz_offset + $qsl_horiz_rst_offset, $qsl_vert_offset, $rst);
        
        $draw->annotation($qsl_horiz_offset + $qsl_horiz_mode_offset, $qsl_vert_offset, $row[$db_COL_MODE]);
    }
}


$image->drawImage($draw);
include_once("qslprintpost.php");
?>
