<?php
//qslfetch.php
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

include_once('../config.php'); // Include configuration file
require_once('backend/db.class.php'); // Include database class
require_once('backend/querybuilder.php'); // Include query builder
include('backend/filecheck.php'); // Include file checking functions
include('backend/backend.php'); // Include backend functions
error_reporting(E_ALL); // Set error reporting to display all errors
ini_set('display_errors', 1); // Set to 1 for development

$base = "/var/www/"; // Base directory path
$base = $base . 'cards/'; // Append 'cards' directory to base path
$results2 = "";

// Check command line arguments
if ($argc != 2 || in_array($argv[1], array('--help', '-help', '-h', '-?'))) {
    // Provide usage information if incorrect arguments are provided
    echo "This is a command line PHP script with one option.\n";
    echo "Usage: {$argv[0]} <option>\n";
    echo "       <option> Enter the QSL number.\n";
    echo "       0 for automatic with SQL.\n";
    echo "       With the --help, -help, -h,\n";
    echo "       or -? options, you can get this help.\n";
} else {
    $Key = $argv[1]; // Get the command line argument
    
    if ($Key == 0) {
        // Automatic update mode
        echo "Auto Update\n";
        $query = getSelect_qsl(); // Get SQL query for automatic update

        try {
            $results = $db->select($query); // Execute SQL query
        } catch (Exception $e) {
            // Handle exceptions
            echo "Query: $query\n";
            echo "Error executing query: " . $e->getMessage() . "\n";
        }
        //var_dump($results);
      
        // Loop through the results
        foreach ($results as $row) {
            
            $iKey = $row['KeyNumber']; // Get QSL KeyNumber
            echo "$iKey\n";

             // Get SQL query and parameters
            $query = getSelect_qsl2();
            $params = array("ikey" => $iKey);

            try {
                // Execute SQL query
                $results2 = $db->select($query, $params);
            } catch (Exception $e) {
                // Handle query execution error
                echo "Error executing query: " . $e->getMessage();
                error_log($e->getMessage());
                return;
            }

            // Check if any results returned
            if (empty($results2)) {
                echo "No results found for iKey: $iKey\n";
                return;
            }

            // Extract data from the first row of the result
            
            //var_dump($results2);
            $firstRow = $results2[0];

            $Call = $firstRow['Call']; // Get Call value
            echo "Call $Call\n";
            $Call_R = str_replace("/", "-", $Call); // Replace '/' with '-'
            $fileMultiply = 1000;
            $groupNumber = floor($iKey / $fileMultiply);
            $FileNoGroup = $groupNumber * $fileMultiply;
            $fileNoGroupHigh = ($groupNumber + 1) * $fileMultiply - 1;
        
            $filePath = $base . $FileNoGroup . "-" . $fileNoGroupHigh; // Define file path
            $pathToThumbs = $filePath . '/thumbs/'; // Define path to thumbnails directory
            $Index = 'received.php'; // Define file name for index
            $HTaccess = '.htaccess'; // Define file name for .htaccess

            // Create directories if they don't exist
            if (!file_exists($filePath)) {
                mkdir($filePath, 0777, true);
                symlink($base . $Index, $filePath . "/" . $Index);
                symlink($base . $HTaccess, $filePath . "/" . $HTaccess);
                mkdir($pathToThumbs, 0777, true);
                symlink($base . $Index, $pathToThumbs . "/" . $Index);
                symlink($base . $HTaccess, $pathToThumbs . "/" . $HTaccess);
            }

            $FileName = "$filePath/E-$iKey-$Call_R.jpg"; // Define file name
            echo "$FileName\n";
            $action = 'update'; // Action type
            $tablename = 'tb_to_download'; // Table name
            // Check if file exists
            if (file_exists($FileName)) 
            {
                // File exists, update database
                echo "The file $FileName exists\n";
                  
            } 
            else 
            {
                // File doesn't exist, sleep and create image
                echo "Sleeping\n";
                sleep(30);
                image($iKey);
            }
            $query = setSelect_QSL();
            $params = array($iKey);
            //$query = "UPDATE `HRD_Web`.`tb_to_download` SET  `updated` = '1' WHERE `tb_to_download`.`KeyNumber` = $iKey";
            try{
                $affectedRows = $db->executeSQL($query, $params,  $action, $tablename);
            }
            catch (Exception $e) 
            {
                echo "Query: " . $query ."\n";
                echo "Error executing query: " . $e->getMessage();
            }
            if ($affectedRows > 0) {
                // Update successful  
                $message = 'Added\n';
                
                echo $message . "\n";
            } else {
                // Update failed
               echo "Update failed ". $query;
            }

            echo "Done with: $iKey\n";
        }
    } else {
        // Image processing mode
        image($Key);
    }
}
?>
