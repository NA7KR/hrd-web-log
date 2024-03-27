<?php
// Copyright notice
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

// Includes
include_once "../qslconf.php"; // Include configuration file
include_once "db_functions.php"; // Include database functions
include_once "email_functions.php"; // Include email functions
include_once "image_functions.php"; // Include image processing functions
require_once '../PHPMailerPro/PHPMailer.Pro.php'; // Include PHPMailerPro library
class_alias("codeworxtech\PHPMailerPro\PHPMailerPro", "PHPMailerPro"); // Alias PHPMailerPro class
if (extension_loaded('mbstring')) {
    
} else {
    echo "The mbstring extension is NOT enabled.";
    exit;
}

$i = 0; // Initialize counter variable

// Error Reporting
if ($debugging === "true") { // Check if debugging is enabled
    error_reporting(E_ALL); // Set error reporting to display all errors
    ini_set('display_errors', '1'); // Display errors
}

// Create database connection
$conn = new mysqli($db_server, $db_user, $db_pass, $db_db); // Establish database connection
if ($conn->connect_error) { // Check for connection errors
    die("Connection failed: " . $conn->connect_error); // Display error message and terminate script
}

// SQL Injection Prevention: Use prepared statements
$insertEmailQuery = $conn->prepare("INSERT INTO TABLE_EMAIL (`$db_COL_PRIMARY_KEY`, `$db_COL_CALL`) 
                                    SELECT T1.`$db_COL_PRIMARY_KEY`, T1.`$db_COL_CALL` 
                                    FROM $db_Table AS T1 
                                    LEFT JOIN TABLE_EMAIL AS T2 ON T1.COL_PRIMARY_KEY = T2.COL_PRIMARY_KEY 
                                    WHERE T1.`$db_COL_PRIMARY_KEY` <> 999999999 AND T2.COL_PRIMARY_KEY IS NULL;"); // Prepare SQL query to insert email records
$insertOptOutQuery = $conn->prepare("INSERT IGNORE INTO TABLE_OPTOUT (`$db_COL_CALL`) 
                                    SELECT * FROM `callsigns`;"); // Prepare SQL query to insert opt-out records
$selectDataQuery = $conn->prepare("SELECT *, DATE_FORMAT(`$db_COL_TIME_ON`, '%m/%d/%Y - %H:%i') AS `kCOL_TIME_ON`
                                    FROM `$db_db`.`$db_Table` 
                                    INNER JOIN `$db_db`.`$db_Table_email` ON `$db_Table`.`$db_COL_PRIMARY_KEY`=`$db_Table_email`.`$db_COL_PRIMARY_KEY` 
                                    INNER JOIN `$db_db`.`TABLE_OPTOUT` ON `$db_Table_email`.`$db_COL_CALL`=`TABLE_OPTOUT`.`$db_COL_CALL` 
                                    AND `$db_Table`.`COL_EMAIL` IS NOT NULL 
                                    AND `TABLE_OPTOUT`.`COL_OPTOUT` <> 'on'
                                    AND (`$db_Table_email`.`COL_SENT` IS NULL OR `$db_Table_email`.`COL_SENT` = '') LIMIT 150;"); // Prepare SQL query to select data

// Execute SQL queries
insertEmailRecords($conn, $insertEmailQuery); // Insert email records
insertOptOutRecords($conn, $insertOptOutQuery); // Insert opt-out records

// Execute selectDataQuery with error handling
$selectDataQuery->execute();
$result = $selectDataQuery->get_result();

// Check for errors in SQL query execution
if (!$result) {
    die("Error executing SQL query: " . $selectDataQuery->error);
}

// HTML output
if ($html === 'True') { // Check if HTML output is enabled
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $callsign; ?> QSL Print System</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/qsl.css" rel="stylesheet">
        <?php include_once "../matomo_tracking.php"; ?> <!-- Include Matomo tracking code -->
    </head>

    <body>
    <header class="shadow-md bg-dark px-3">
        <body  class="background1">
        <div class="header">
            <a href="#default" class="logo">QSL Cards Builder</a>
            <div class="header-right">
                <a class="active" href="https://qsl.na7kr.us">QSL Builder</a>
                <a href="https://na7kr.na7kr.us">QSL Viewer</a>
                <a href="https://qsl.na7kr.us/stopstart.php">Stop Email ( Opt Out) </a>
                <a href="https://eqsl.cc" target="_blank">EQSL</a>
                <a href="https://clublog.org" target="_blank">Clublog</a>
                <a href="https://lotw.arrl.org" target="_blank">LOTW</a>
                <a href="https://qsl.na7kr.us/about.php">About</a>
            </div>
        </div>
    </header>

    <main>
    <div class="container">
    <?php
}

// Data processing and email sending loop
while ($row = $result->fetch_assoc()) { // Loop through selected data
    $i++; // Increment counter
    // Variable initialization
    $freqband = sprintf("%.3f", $row[$db_COL_FREQ] / 1000000); // Calculate frequency band
    $rst = is_int(strlen($row[$db_COL_RST_RCVD])) ? $row[$db_COL_RST_RCVD] : (strcasecmp($row[$db_COL_MODE], "CW") || strcasecmp($row[$db_COL_MODE], "cw") ? "599" : "59"); // Set RST value
    $COL_PRIMARY_KEY = $row[$db_COL_PRIMARY_KEY]; // Set primary key
    $COL_EMAIL = $row[$db_COL_EMAIL]; // Set email
    $call = $row[$db_COL_CALL]; // Set call sign
    $COL_SENT = $row['COL_SENT']; // Set sent status
    $COL_MODE = $row[$db_COL_MODE]; // Set mode
    $COL_BAND = $row[$db_COL_BAND]; // Set band
    $COL_RST_SENT = $row[$db_COL_RST_SENT]; // Set sent RST
    $COL_ANTENNA = $row[$db_COL_Antenna]; // Set antenna
    $COL_MY_RIG = $row[$db_COL_MY_RIG]; // Set rig
    $COL_TIME_ON = sprintf(" %sZ  ", $row['kCOL_TIME_ON']); // Set time

    // Debugging output
    if ($debugging === "true") { // Check if debugging is enabled
        echo "ID: $COL_PRIMARY_KEY<br>"; // Output primary key
        echo "CALL: $call<br>"; // Output call sign
        echo "EMAIL: $COL_EMAIL<br>"; // Output email
        echo "SENT: $COL_SENT<br>"; // Output sent status
        echo "UTC $COL_TIME_ON<br>"; // Output time
        echo "Band $COL_BAND<br>"; // Output band
        echo "Frequency $freqband<br>"; // Output frequency
        echo "Mode $COL_MODE<br>"; // Output mode
        echo "RST Received $rst<br>"; // Output received RST
        echo "My Rig $COL_MY_RIG <br>"; // Output rig
        echo "RST Sent $COL_RST_SENT<br><br>"; // Output sent RST
    }

    // Processing and sending emails
    $qstring = " $COL_TIME_ON $freqband $rst $COL_MODE"; // Construct email content
    processImage($qsl_template, $qsl_c_font_color, $qsl_c_font, $qsl_c_font_size, $qsl_c_font_aa, $qsl_callsign_center_gravity, $qsl_callsign_horiz_offset, $qsl_callsign_vert_offset, $qsl_font_color, $qsl_font, $qsl_font_size, $qsl_font_aa, $qsl_qso_center_gravity, $qsl_horiz_offset, $qsl_vert_offset, $debugging, $row, $qsltxt, $freqband, $rst, $qsl_qso_print_operator, $qsl_qso_print_opercounty); // Process QSL image
    sendEmail($testing, $html, $COL_PRIMARY_KEY, $COL_EMAIL, $call, $COL_TIME_ON, $COL_BAND, $freqband, $COL_MODE, $rst, $COL_RST_SENT, $COL_MY_RIG, $COL_ANTENNA, $qsl_template, $qsltxt, $qsl_c_font_color, $qsl_c_font, $qsl_c_font_size, $qsl_c_font_aa, $qsl_callsign_center_gravity, $qsl_callsign_horiz_offset, $qsl_callsign_vert_offset, $qsl_font_color, $qsl_font, $qsl_font_size, $qsl_font_aa, $qsl_qso_center_gravity, $qsl_horiz_offset, $qsl_vert_offset, $senderemail, $emailserver, $emailpost, $emailpassword, $site_url, $callsign, $name, $db_server, $db_user, $db_pass, $db_db); // Send email
}

echo $i . PHP_EOL; // Output total count of emails sent

// HTML output closing tags
if ($html === 'True') { // Check if HTML output is enabled
    ?>
    </div>
    </main>
    <footer>
        <div class="d-flex">
            <p class="text-muted">Site information &copy;&nbsp;<?= date("Y"); ?>&nbsp;<?= $station_name; ?><br/>
        </div>
    </footer>
    <script src="js/bootstrap.min.js"></script>
    </body>
    </html>
    <?php
}
?>
