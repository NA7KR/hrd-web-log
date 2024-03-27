<?php
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
// Function to process and generate QSL image
function processImage($qsl_template, $qsl_c_font_color, $qsl_c_font, $qsl_c_font_size, $qsl_c_font_aa, $qsl_callsign_center_gravity, $qsl_callsign_horiz_offset, $qsl_callsign_vert_offset, $qsl_font_color, $qsl_font, $qsl_font_size, $qsl_font_aa, $qsl_qso_center_gravity, $qsl_horiz_offset, $qsl_vert_offset, $debuging, $row, $qsltxt, $freqband, $rst, $qsl_qso_print_operator, $qsl_qso_print_opercounty)
{
    include("../qslconf.php");
    
    // Initialize Imagick object
    $image = new Imagick("../" . $qsl_template);

    // Set image format to JPG
    $image->setImageFormat('jpg');

    // Extract data from the row
    $COL_PRIMARY_KEY = $row[$db_COL_PRIMARY_KEY];
    $COL_EMAIL = $row[$db_COL_EMAIL];
    $call = $row[$db_COL_CALL];
    $COL_TIME_ON = sprintf(" %sZ  ", $row[$db_COL_TIME_ON ]);
    $COL_BAND = $row[$db_COL_BAND];
    $COL_FREQ = $row[$db_COL_FREQ];
    $COL_MODE = $row[$db_COL_MODE];
    $COL_RST_RCVD = $row[$db_COL_RST_RCVD];
    $COL_RST_SENT = $row[$db_COL_RST_SENT];
    $COL_MY_RIG = $row[$db_COL_MY_RIG];
    $COL_ANTENNA = $row[$db_COL_Antenna];
   
    // Draw Callsign
    $draw = new ImagickDraw();
    $color = new ImagickPixel($qsl_c_font_color);
    $draw->setFont($qsl_c_font);
    $draw->setFontSize($qsl_c_font_size);
    $draw->setFillColor($color);
    $draw->setStrokeAntialias($qsl_c_font_aa);
    $draw->setTextAntialias($qsl_c_font_aa);
    if ($qsl_callsign_center_gravity) {
        $image->setGravity(Imagick::GRAVITY_CENTER);
    }
    $call2 = $qsltxt . $call;
    $draw->annotation($qsl_callsign_horiz_offset, $qsl_callsign_vert_offset, $call2);
    $image->drawImage($draw);

    if ($qsl_callsign_center_gravity) {
        $image->setGravity(Imagick::GRAVITY_NORTHWEST);
    }

    // Draw QSO(s)
    $draw = new ImagickDraw();
    $color = new ImagickPixel($qsl_font_color);
    $draw->setFont($qsl_font);
    $draw->setFontSize($qsl_font_size);
    $draw->setFillColor($color);
    $draw->setStrokeAntialias($qsl_font_aa);
    $draw->setTextAntialias($qsl_font_aa);

    if ($qsl_qso_center_gravity) {
        $image->setGravity(Imagick::GRAVITY_CENTER);
    }

    // Construct QSO information string
    $qstring = " " . $COL_TIME_ON . " " . $freqband . " " . $rst . " " . $COL_MODE;

    
    $draw->annotation($qsl_horiz_offset, $qsl_vert_offset, $qstring);

    $image->drawImage($draw);

    // Reset gravity
    if ($qsl_qso_center_gravity) {
        $image->setGravity(Imagick::GRAVITY_NORTHWEST);
    }

    
    $draw->annotation($qsl_horiz_offset, $qsl_vert_offset, $qstring);
    $COL_MY_RIG = $row[$db_COL_MY_RIG];

    // Remove slashes and replace with dashes in the file name
    $call = str_replace('/', '-', $call);

    // Ensure secure file name by only allowing alphanumeric characters, dashes, and underscores
    $safe_call = preg_replace('/[^a-zA-Z0-9_-]/', '', $call);
  
    // Save the image
    try {
        // Construct the image file path
        $imgfile = "../cards/" . $safe_call . "-" . $COL_PRIMARY_KEY . ".jpg";
        // Write the image file
        $image->writeImages($imgfile, true);
    } catch (Exception $e) {
        // Handle any exceptions
        echo $imgfile . "<br>" ;
        echo $e;
    }
}
?>
