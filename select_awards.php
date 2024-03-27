<?php
// select_awards.php
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
  
// Include necessary files
$filePath = "/Awards";
$title = "Awards";

include_once("../config.php");
require_once('backend/db.class.php');
require_once("backend/backend.php");
require_once("backend/querybuilder.php");
include('backend/header.php');

// Create a new instance of the Db class




// Define the file path for the images



    // Execute the SELECT query using the new select method
    $query = getSelect_Award();

    try {
        $results = $db->select($query);
    } catch (Exception $e) {
        echo "Query: " . $query ."<br>";
        echo "Error executing query: " . $e->getMessage();
    }

    // Start building the table
    echo "<div class='centered-content'>"; // Add a container div
    echo "<table class='custom-table' border='0'><tbody><tr><th>Award</th><th>File</th></tr>";

    // Loop through the results and display each row
    foreach ($results as $i => $row) {
        // Get the file name from the row
        $fileName = $row['File'];
        
        // Determine the row color based on even or odd index
        $rowColor = ($i % 2 == 0) ? 'even-row' : 'odd-row';
        
        // Start a new table row with alternating color
        echo "<tr class='$rowColor'>";
        
        // Add the award name to the table
        echo "<td>{$row['Award']}</td>" . PHP_EOL;
        
        // Add the image link to the table
        echo "<td><a href='$filePath/$fileName'><img src='$filePath/thumbs/$fileName' alt='$fileName'></a></td>";
        
        // Close the table row
        echo "</tr>";
    }

    // Close the table and container div
    echo "</tbody></table>";
    
    // Execute the COUNT query
    $query = getSelect_AwardsCount();
    try{
        $count = $db->executeCount($query);
    } catch (Exception $e) {
        echo "Query: " . $query ."<br>";
        echo "Error executing query: " . $e->getMessage();
    }

    // Display the count
    echo "<p class='total-count'>Total records: $count</p>";
    echo "</div>"; // Close the container div

include('backend/footer.php');
?>
