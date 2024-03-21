<?php
// home.php 
// NEW
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

// Description: Home page for the QSL printing system with enhanced security and coding standards.


// Initialize all required PHP files and settings
require_once '../config.php'; // Consolidate all initial settings and class inclusions here
require_once 'backend/db.class.php'; // Database class for handling database operations
require_once("backend/querybuilder.php");


// Set appropriate content-type for HTML and define character set for security
header('Content-Type: text/html; charset=utf-8');

// Securely fetch and sanitize the database table name from a configuration or constant
$db_Table = htmlspecialchars(DB_TABLE_HRD); // Assume DB_TABLE is defined in 'init.php' or '../config.php'

// Construct a secure SQL query using placeholders
$query = getSelect_Home();

// Execute the query securely
try {
    $results = $db->select($query);
} catch (Exception $e) {
    echo "Query: " . $query ."<br>";
    echo "Error executing query: " . $e->getMessage();
}

// Initialize variables for dates
$firstdate = '';
$lastdate = '';

// Validate and assign the query results
if (!empty($results)) {
    $firstdate = htmlspecialchars($results[0]['first_date']);
    $lastdate = htmlspecialchars($results[0]['last_date']);
} else {
    // Provide feedback if no records found
    $noRecordsMessage = "No QSL records found.";
}

// Set page metadata
$page_title = "Home - Your Website";
$current_page = basename(__FILE__);

$java = "";
// Include page header
include_once 'backend/header.php';
?>

<main class="main">
    <div class="container">
        <!-- Content Row -->
        <div class="row">
            <div class="col-12 my-2 text-center">
                <!-- Dynamically display station name and callsign -->
                <h3><?= htmlspecialchars($station_name); ?> - <?= htmlspecialchars($callsign); ?></h3>
            </div>
        </div>
        <!-- QSL Information Row -->
        <div class="row justify-content-around">
            <div class="col-md-5">
                <!-- Welcome message and QSL instructions -->
                <p>Welcome to the <?= htmlspecialchars($station_name); ?> QSL printing system.</p>
                <p>Retrieve and print QSL cards for QSOs with the call sign <?= htmlspecialchars($callsign); ?>.</p>
                <p>QSL cards for all recorded QSOs are available.</p>
                <p>Start by entering your call sign below and clicking 'Search for QSOs'.</p>
                <hr>
                <!-- User input form -->
                <form action="qslfetch.php" method="post">
                    <label for="call" class="form-label"><strong>Enter Call Sign to Search For:</strong></label>
                    <input type="text" name="call" class="form-control" required>
                    <button type="submit" class="btn btn-primary my-2">Search for QSOs</button>
                </form>
            </div>
            <div class="col-md-5">
                <!-- Display first and last QSO dates -->
                <div class="alert alert-secondary">
                    <b>First QSO Date:</b> <?= $firstdate ?: 'Not available'; ?><br>
                    <b>Last QSO Date:</b> <?= $lastdate ?: 'Not available'; ?>
                    <hr>
                    <!-- Additional dynamic notes -->
                    <div class="qsl-notes">
                        <?= $qslNotes; ?>
                    </div>
                  
                </div>
            </div>
        </div>
        <!-- Additional content could go here -->
    </div>
</main>

<?php include 'backend/footer.php'; ?>
</body>
</html>
