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
include_once("../config.php");
//require_once('db.class.php');
$browscapDirective = ini_get('browscap');

if (!$browscapDirective) {
    echo "The 'browscap' directive is not set.";
} else {
 

    // Get the server name
    $url = $_SERVER['SERVER_NAME'];

    // Get the IP Address
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    // Get the script name
    $scriptName = $_SERVER['SCRIPT_NAME'];

    // Format the URL
    $visitURL = "http://$url$scriptName"; // Assuming you want to include 'http://' in the URL

    // Get browser information
    $browser = get_browser(null, true);

    // Format the browser info
    $browser_type = $browser['browser'];

    // Format the browser version
    $version = $browser['version'];

    // Format the OS
    $os = str_replace("Win", "Windows ", $browser['platform']);

    // Format the OS version
    $user_os = str_replace("Win", "Windows ", $browser['platform_version']);

    // Print or use the variables as needed
    //echo "Server Name: $url<br>";
    //echo "IP Address: $ipAddress<br>";
    //echo "Script Name: $scriptName<br>";
    //echo "Visit URL: $visitURL<br>";
    //echo "Browser: $browser_type<br>";
    //echo "Browser Version: $version<br>";
    //echo "Operating System: $os<br>";
    //echo "Operating System Version: $user_os<br>";

    // Get the URL
    $url = $_SERVER['SERVER_NAME'];
    // Get the IP Address
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    // Get the script name
    $scriptName = $_SERVER['SCRIPT_NAME'];
    //format the URL
    $visitURL = $url.$scriptName;
    // Get the URL
    $url = $_SERVER['SERVER_NAME'];
    // Get the IP Address
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    // Get the script name
    $scriptName = $_SERVER['SCRIPT_NAME'];
    //format the URL
    $visitURL = $url.$scriptName;
    //must have browser.ini in php.ini file
    $browser = get_browser(null, true);
    //print_r ($browser['browser']);
    //format the browser info
    $browser_type =  $browser['browser'];
    //format the browser version
    $version = $browser['version'] ;
    //format the OS
    $os = str_replace("Win", "Windows ", $browser['platform']);
    //format the OS version
    $user_os = str_replace("Win", "Windows ", $browser['platform_version']);
    // Call the function to get the SQL query
    $query = setHeader_browser($ipAddress, $visitURL, $browser_type, $version, $os, $user_os);

    // Bind parameters
    $params = array(':visitorIP' => $ipAddress, ':visitURL' => $visitURL, ':browser' => $browser_type, ':bversion' => $version, ':os' => $os, ':osversion' => $user_os);

    // Assuming $db is your database connection object
    $action = 'insert'; // Action type
    $tablename = DB_Table_visit; // Table name

    // Execute the update query
    try {
        $results = $db->select($query,$params); //note switch to this so does not track browser insersts to db_log
        //$results = $db->executeSQL($query, $params, $action, $tablename);
    } catch (Exception $e) {
        echo "Query: " . $query . "<br>";
        echo "Error executing query: " . $e->getMessage();
    }
}
?>
