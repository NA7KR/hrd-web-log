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
ini_set('error_reporting', E_ALL);

// Enable displaying errors
ini_set('display_errors', '1');
$java = true; 
// Include database connection
//require_once("db.php");
include_once("backend/backend.php");
include_once('../config.php');
include_once("backend/querybuilder.php");


// Set page title and current page
$page_title = "Received Cards";
$current_page = basename(__FILE__);

// Include header
include_once("backend/header.php");
?>

<main class="main">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 my-2 text-center"> <!-- Added text-center class -->
                <h3>Kevin NA7KR Salem Oregon - NA7KR</h3>
            </div>
        </div>
        <div class="auto-style1">
            <div class="auto-style1 text-center"> <!-- Applying text-center class -->
                Hello welcome my log book at reads from Ham Radio Deluxe log..
            </div>
        </div>       
  
        <br><br>
        <?php
        //echo safe("this%20is\na&nbsb;'test';");
        if (isset($_POST['Submit1'])) {
            $LOG  = htmlspecialchars($_POST["Log"]);
            $data = '<form name ="form1" method ="post" action ="received.php">' . PHP_EOL;
            $data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
            $data .= '<input type="hidden" name="Submit" value= "true">' . PHP_EOL;
            $data .= '<input type="hidden" name="1st" value= "true">' . PHP_EOL;
            echo $data;
            $data = "";

            $query = getSelect_Name();
            $params = array("f" => $LOG);

            $results = $db->select($query, $params); // Replace $yourDatabaseObject with your actual database connection object

            // Validate and assign the query results
            if (!empty($results)) {
                $Select_File = htmlspecialchars($results[0]['Select_File']);
                //echo $Select_File . "<br>" ;
               
            } else {
                // Provide feedback if no records found
                echo  "No QSL records found.<br>";
                echo $sql . " where f = ". $LOG . "<br>";
            }

            include ($Select_File);
        } 
        else 
        {   

            echo generateSelectForm();
            //echo select($conn);
            ?>
          
            <p>
            </div>      
                <?php   
                echo \OptionList(false, false, false, false, false, false) . \PHP_EOL;     
        }

        include ('backend/footer.php'); 
        ?>
    </body>
</html>