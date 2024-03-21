<?php
// received.php
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


// Set error reporting to display all errors
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');

// Include necessary files
require_once("backend/backend.php");
require_once('../config.php');
require_once("backend/querybuilder.php");

// Set page title and current page
$page_title = "Start or Stop Email of Cards";
$current_page = basename(__FILE__);
$current = "";

// Include header
include_once("backend/header.php");

// Process form submission
if (!empty($_POST)) {
    // Initialize $results variable
    $results = null;

    // Get callsign and email from the form
    $CALL = $_POST["call"];
    $EMAIL = $_POST["email"];

    // Prepare SQL statement to check if callsign and email match in the database
    $query = getSelect_startstop();
    $params = array(':call' => $CALL, ':email' => $EMAIL);
    
    try {
        // Execute the query
        $results = $db->select($query, $params);
        
        // Check if any rows were returned
        if (!empty($results)) {
            // Rows were found, process the results
            foreach ($results as $row) {
                // Get the current optout status
                $current = $row['COL_OPTOUT'];
            }
        } else {
            // No rows were found
            echo "No matching records found.";
        }
    } catch (Exception $e) {
        // Handle exceptions
        echo "Error executing query: " . $e->getMessage();
    }

    // Check if the optout checkbox is selected
    if (isset($_POST['optout'])) {
        $COL_OPTOUT = "on";
        $message = '<h1 style="color:Red;">You will not receive a card next time</h1>';
    } else {
        $COL_OPTOUT = "off";
        $message = '<h1 style="color:Blue;">You will receive a card next time</h1>';
    }
    
    try {
        // Prepare SQL statement to update optout status
        $query = setSelect_OptOut();
        $params = array($COL_OPTOUT, $CALL);
        $action = 'update'; // Action type
        $tablename = DB_Table_optout; // Table name
        // Execute the update query
        $affectedRows = $db->executeSQL($query, $params,  $action, $tablename);
    
        if ($affectedRows > 0) {
            // Update successful
            ?>
            <div class="container" style="display: flex; justify-content: center; align-items: center;">
            <?php
            if ($COL_OPTOUT == "on") {
                $message = '<h1 style="color:Red;">You will not receive a card next time</h1>';
            } else {
                $message = '<h1 style="color:Blue;">You will receive a card next time</h1>';
            }
            echo $message . "</div>";
        } else {
            // Update failed
            if ($COL_OPTOUT == $current) {
                echo "You are already set";
            } else {
                echo "Update operation failed..";
            }
        }
    } catch (Exception $e) {
        // Handle exceptions
        echo "Error: " . $e->getMessage();
    }
} else {
    // Display form for opting in/out
?>
    <main>
        <div class="container" style="display: flex; justify-content: center; align-items: center;">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <table>
                    <tr>
                        <td>Callsign: </td>
                        <td><input type="text" name="call"></td>
                    </tr>
                    <tr>
                        <td>Email: </td>
                        <td><input type="email" name="email"></td>
                    </tr>
                </table>
                <p>To opt out of cards being emailed (Do not select cards above to opt in or out)</p>
                <input type="checkbox" id="optout" name="optout"/> If unchecked will get card next time.<br>
                <input type="submit" class="btn btn-secondary"><br>
            </form>
        </div>
    </main>
<?php
    // Include footer
    include ('backend/footer.php'); 
}
?>
</body>
</html>
