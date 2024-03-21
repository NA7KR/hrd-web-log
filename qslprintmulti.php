<?php
// qslprintmulti.php
/*
Copyright NA7KR Kevin Roberts 2024

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


// Include configuration files and pre-processing script
include_once "qslprintpre.php";

try {
    // Initialize the ImageMagick item and set image format to PDF
    $image = new Imagick('images/' . $qsl_template_multi);
    $image->setImageFormat('pdf');
} catch (ImagickException $e) {
    // Handle Imagick initialization error
    echo 'Imagick initialization failed: ' . $e->getMessage();
    exit;
}

// Draw the Callsign
$draw = new ImagickDraw();
$color = new ImagickPixel($qsl_c_font_color);
$draw->setFont($qsl_c_font);
$draw->setFontSize($qsl_c_font_size);
$draw->setFillColor($color);
$draw->setStrokeAntialias($qsl_c_font_aa);
$draw->setTextAntialias($qsl_c_font_aa);
if($qsl_callsign_center_gravity_multi){
    $image->setGravity(imagick::GRAVITY_CENTER);
}
$call2 = $qsltxt  . $call;
$draw->annotation($qsl_callsign_horiz_offset_multi, $qsl_callsign_vert_offset_multi, $call2);
$image->drawImage($draw);

// Reset gravity if necessary
if($qsl_callsign_center_gravity_multi){
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

if($qsl_qso_center_gravity_multi){
    $image->setGravity(imagick::GRAVITY_CENTER);
}

$lcount = 0; // Initialize line count

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

// Loop through each row of QSO data
foreach ($results as $row) {
    // Check if verbose recording of QSO data is enabled
    if ($qsl_qso_verbose_rec_multi) {
        // Format QSO data string
        $freqband = (strlen($row[$db_COL_FREQ]) > 0) ? sprintf("%.3f", $row[$db_COL_FREQ]/1000000) : $row[$db_COL_BAND];
        $rst = (is_numeric($row[$db_COL_RST_RCVD])) ? $row[$db_COL_RST_RCVD] : ((strcasecmp($row[$db_COL_MODE], "CW") === 0 || strcasecmp($row[$db_COL_MODE], "cw") === 0) ? "599" : "59");

        $qstring = sprintf("%s %sZ  Freq: %sMhz  RST: %s  Mode: %s",
            '', $row['kCOL_TIME_ON'], $freqband, $rst, $row[$db_COL_MODE]);
       
        // Draw QSO data on image
        $draw->annotation($qsl_horiz_offset_multi, 
            $qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier), $qstring);

    } else { // If verbose recording of QSO data is not enabled

        // Draw individual QSO data fields
        $draw->annotation(
            $qsl_horiz_offset_multi, 
            $qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier), 
            $row['qsodate']
            );
        $draw->annotation(
            $qsl_horiz_offset_multi + $qsl_horiz_timeon_offset, 
            $qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier), 
            $row['kCOL_TIME_ON'] . "Z"
            );
            
        $freqband = (strlen($row[$db_COL_FREQ]) > 0) ? sprintf("%.3f", $row[$db_COL_FREQ]/1000000) : $row[$db_COL_BAND];
        $draw->annotation(
            $qsl_horiz_offset_multi + $qsl_horiz_band_offset,
            $qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier), 
            $freqband
            );
        
        $rst = (is_numeric($row[$db_COL_RST_RCVD])) ? $row[$db_COL_RST_RCVD] : ((strcasecmp($row[$db_COL_MODE], "CW") === 0 || strcasecmp($row[$db_COL_MODE], "cw") === 0) ? "599" : "59");
        $draw->annotation(
            $qsl_horiz_offset_multi + $qsl_horiz_rst_offset_multi, 
            $qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier),
            $rst
            );

        $draw->annotation(
            $qsl_horiz_offset_multi + $qsl_horiz_mode_offset_multi, 
            $qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier),
            $row[$db_COL_MODE]
            );
    }



    $image->drawImage($draw); // Draw QSO data on image
    $lcount++; // Increment line count
}

// Include post-processing script
include_once("qslprintpost.php");
?>
